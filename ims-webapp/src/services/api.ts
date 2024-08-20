import {env} from "../env.ts";

class Api {
    private async fetch<ResponseType>({ body, method, path }: { path: string, body?: object, method: 'POST' | 'GET' | 'DELETE' }): Promise<ResponseType> {
        const token = localStorage.getItem('token')
        const response = await fetch(`${env.api.baseUrl}${path}`, {
            headers: {
                'accept': 'application/json',
                'content-type': 'application/json',
                ...(token ? { 'authorization': `Bearer ${token}` } : {}),
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
}

export default new Api()
