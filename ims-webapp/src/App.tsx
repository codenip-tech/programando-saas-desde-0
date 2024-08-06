import { useState } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'

function App() {
  const [username, setUsername] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const [token, setToken] = useState('')

  async function login() {
      setError('')
      const response = await fetch('http://127.0.0.1:1000/api/login', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
          },
          body: JSON.stringify({
              username,
              password,
          })
      })
      if (!response.ok) {
          setError('Failed to login')
          return
      }
      setPassword('')
      setUsername('')
      const jsonResponse = await response.json() as { token: string }
      setToken(jsonResponse.token)
  }

  async function ping() {
      console.log(await fetch('http://127.0.0.1:1000/api/ping', {
          headers: {

          }
      }).then(response => response.json()))
    }

  return (
    <>
      <div>
        <a href="https://vitejs.dev" target="_blank">
          <img src={viteLogo} className="logo" alt="Vite logo" />
        </a>
        <a href="https://react.dev" target="_blank">
          <img src={reactLogo} className="logo react" alt="React logo" />
        </a>
      </div>
      <h1>Vite + React</h1>
      <div className="card">
          <span>{error}</span> <br />
        <label htmlFor="username">Username</label>
        <input value={username} onChange={event => { setUsername(event.target.value) }} id="username" />
        <label htmlFor="password">Password</label>
        <input value={password} onChange={(event) => { setPassword(event.target.value) }} id="password" type="password" />
        <button onClick={login}>
          Login
        </button>
        <button onClick={ping}>
          Ping
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

export default App
