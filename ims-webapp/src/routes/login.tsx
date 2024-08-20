import {useEffect, useState} from 'react'
import '@/App.css'
import {Navigate} from "react-router-dom";
import {routes} from "./route-names.ts";
import api from "../services/api.ts";

const tokenStorageKey = 'token'

function Login() {
    const [email, setEmail] = useState('')
    const [password, setPassword] = useState('')
    const [error, setError] = useState('')
    const [token, setToken] = useState(
        localStorage.getItem(tokenStorageKey)
    )
    const [toDashboard, setToDashboard] = useState(false)
    const [toRegister, setToRegister] = useState(false)
    useEffect(() => {
        console.log('token', token)
        if (!token) {
            return
        }
        localStorage.setItem(tokenStorageKey, token)
        setToDashboard(true)
    }, [token]);

    if (toDashboard) {
        return <Navigate to={routes.dashboard} />
    }
    if (toRegister) {
        return <Navigate to={routes.register} />
    }

    async function login() {
        setError('')
        try {
            const response = await api.login({
                email: email,
                password: password
            })
            setPassword('')
            setEmail('')
            setToken(response.token)
        } catch (e) {
            setError('Failed to login')
        }

    }

    return (
        <>
            <h1>Login</h1>
            <div className="card">
                <span>{error}</span> <br />
                <label htmlFor="username">Username</label>
                <input value={email} onChange={event => { setEmail(event.target.value) }} id="username" />
                <label htmlFor="password">Password</label>
                <input value={password} onChange={(event) => { setPassword(event.target.value) }} id="password" type="password" />
                <button onClick={login}>
                    Login
                </button>
                <button onClick={() => setToRegister(true)}>
                    Register
                </button>
                <p>
                    Edit <code>src/App.tsx</code> and save to test HMR
                </p>
            </div>
            <p className="read-the-docs">
                Click on the Vite and React logos to learn more
            </p>
        </>
    )
}

export default Login
