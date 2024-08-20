import {useState} from "react";
import { Navigate } from "react-router-dom";
import {routes} from "./route-names.ts";
import api from "../services/api.ts";


function Register() {
    const [email, setEmail] = useState('')
    const [password, setPassword] = useState('')
    const [error, setError] = useState('')
    const [toLogin, setToLogin] = useState(false)


    if (toLogin) {
        return <Navigate to={routes.login} />;
    }

    async function register() {
        setError('')
        try {
            await api.register({
                email,
                password,
            })
            setPassword('')
            setEmail('')
            setToLogin(true)
        } catch (e) {
            console.error(e)
            setError('Failed to register')
        }
    }
    return (
        <>
            <h1>Register</h1>
            <div className="card">
                <span>{error}</span> <br />
                <label htmlFor="username">Username</label>
                <input value={email} onChange={event => { setEmail(event.target.value) }} id="username" />
                <label htmlFor="password">Password</label>
                <input value={password} onChange={(event) => { setPassword(event.target.value) }} id="password" type="password" />
                <button onClick={register}>
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

export default Register
