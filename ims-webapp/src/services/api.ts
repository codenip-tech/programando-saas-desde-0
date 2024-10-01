import {env} from "../env.ts";
import selectedOrganizationStorage from "./selected-organization-storage.ts";

class Api {
    private async fetch<ResponseType>({ body, method, path }: { path: string, body?: object, method: 'POST' | 'GET' | 'DELETE' }): Promise<ResponseType> {
        const token = localStorage.getItem('token')
        const selectedOrganization = selectedOrganizationStorage.getOrg()
        const response = await fetch(`${env.api.baseUrl}${path}`, {
            headers: {
                'accept': 'application/json',
                'content-type': 'application/json',
                ...(token ? { 'authorization': `Bearer ${token}` } : {}),
                ...(token && selectedOrganization ? { 'x-organization-id': String(selectedOrganization.id) } : {})
            },
            body: body ? JSON.stringify(body) : undefined,
            method: method,
        })

        if (!response.ok) {
            // @todo Improve error shown based on api response
            throw new Error('Api failed')
        }

        return await response.json() as ResponseType
    }

    public register({ email, password }: { email: string, password: string }) {
        return this.fetch({
            method: 'POST',
            path: '/auth/register',
            body: {
                email: email,
                password: password,
            }
        })
    }

    public login({ email, password }: { email: string, password: string }) {
        return this.fetch<{ token: string }>({
            method: 'POST',
            path: '/auth/login',
            body: {
                email: email,
                password: password,
            }
        })
    }

    public ping() {
        return this.fetch({
            path: '/ping',
            method: 'GET',
        })
    }

    public getProducts() {
        return this.fetch<{ products: { id: number, name: string }[] }>({
            path: '/product',
            method: 'GET',
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
                name,
                tagIds,
            }
        })
    }

    public createProduct({ name }: { name: string }) {
        return this.fetch<{ id: number }>({
            path: `/product`,
            method: 'POST',
            body: {
                name,
            }
        })
    }

    public createTag({ name }: { name: string }) {
        return this.fetch<{ tag: { id: number } }>({
            path: `/tag`,
            method: 'POST',
            body: {
                name,
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
