import {env} from "../env.ts";
import selectedOrganizationStorage from "./selected-organization-storage.ts";

export type GetProductsInput = {
    sort: { field: string, direction: 'asc' | 'desc' } | null
    filter: { field: string, value: string } | null
}

type RequestBody = { type: 'files', files: Record<string, File> } | { type: 'json', content: object }

class Api {
    private serializeBody(requestBody?: RequestBody) {
        if (!requestBody) return undefined
        if (requestBody.type === 'json') return JSON.stringify(requestBody.content)
        const data = new FormData()
        for (const [fieldName, file] of Object.entries(requestBody.files))
        data.append(fieldName, file)

        return data
    }

    private async fetch<ResponseType>({ body, method, path }: { path: string, body?: RequestBody, method: 'POST' | 'GET' | 'DELETE' }): Promise<ResponseType> {
        const token = localStorage.getItem('token')
        const selectedOrganization = selectedOrganizationStorage.getOrg()
        const response = await fetch(`${env.api.baseUrl}${path}`, {
            headers: {
                'accept': 'application/json',
                ...(body?.type === 'json' ? { 'content-type': 'application/json' } : {}),
                ...(token ? { 'authorization': `Bearer ${token}` } : {}),
                ...(token && selectedOrganization ? { 'x-organization-id': String(selectedOrganization.id) } : {})
            },
            body: this.serializeBody(body),
            method: method,
        })

        if (!response.ok) {
            // @todo Improve error shown based on api response
            throw new Error('Api failed')
        }

        return response.headers.get('content-type') === 'application/json' ?
            await response.json() as ResponseType :
            await response.blob() as ResponseType
    }

    public register({ email, password }: { email: string, password: string }) {
        return this.fetch({
            method: 'POST',
            path: '/auth/register',
            body: {
                type: 'json',
                content: {
                    email: email,
                    password: password,
                }
            }
        })
    }

    public async getPortalUrl() {
        const response = await this.fetch<{ url: string }>({
            method: 'POST',
            path: '/billing/portal-url',
        })

        return response.url
    }

    public async onCheckoutSuccess() {
        const response = await this.fetch<{ success: true }>({
            method: 'POST',
            path: '/billing/on-successful-checkout',
        })

        return response.success
    }

    public importFile(file: File) {
        return this.fetch({
            method: 'POST',
            path: '/product/import',
            body: {
                type: 'files',
                files: { file: file },
            }
        })
    }

    public login({ email, password }: { email: string, password: string }) {
        return this.fetch<{ token: string }>({
            method: 'POST',
            path: '/auth/login',
            body: {
                type: 'json',
                content: {
                    email: email,
                    password: password,
                }
            }
        })
    }

    public ping() {
        return this.fetch({
            path: '/ping',
            method: 'GET',
        })
    }

    public getProducts({ sort, filter }: GetProductsInput) {
        return this.fetch<{ products: { id: number, name: string }[] }>({
            path: '/product/list',
            method: 'POST',
            body: {
                type: 'json',
                content: {
                    sort: sort,
                    filter: filter,
                }
            },
        })
    }

    public exportProducts({ sort, filter }: GetProductsInput) {
        return this.fetch<Blob>({
            path: '/product/export',
            method: 'POST',
            body: {
                type: 'json',
                content: {
                    sort: sort,
                    filter: filter,
                }
            },
        })
    }

    public getProduct(id: number) {
        return this.fetch<{ product: { id: number, name: string, tagIds: number[] } }>({
            path: `/product/${id}`,
            method: 'GET',
        })
    }

    public updateProduct({ id, name, tagIds }: { id: number, name: string, tagIds: number[] }) {
        return this.fetch({
            path: `/product/${id}`,
            method: 'POST',
            body: {
                type: 'json',
                content: {
                    name,
                    tagIds,
                }
            }
        })
    }

    public createProduct({ name }: { name: string }) {
        return this.fetch<{ id: number }>({
            path: `/product`,
            method: 'POST',
            body: {
                type: 'json',
                content: {
                    name,
                }
            }
        })
    }

    public createTag({ name }: { name: string }) {
        return this.fetch<{ tag: { id: number } }>({
            path: `/tag`,
            method: 'POST',
            body: {
                type: 'json',
                content: {
                    name,
                }
            }
        })
    }

    public getTags() {
        return this.fetch<{ tags: { id: number, name: string }[] }>({
            path: `/tag`,
            method: 'GET',
        })
    }

    public getMyOrganizations() {
        return this.fetch<{ organizations: { id: number, name: string }[] }>({
            path: '/organization',
            method: 'GET',
        })
    }
}

export default new Api()
