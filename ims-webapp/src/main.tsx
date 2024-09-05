import React from 'react'
import ReactDOM from 'react-dom/client'
import './index.css'
import {
    createBrowserRouter, Navigate,
    RouterProvider,
} from "react-router-dom";
import Login from "./routes/login.tsx";
import Register from "./routes/register.tsx";
import Dashboard from "./routes/dashboard.tsx";
import PrivateRoute from "./routes/protected-route.tsx";
import {appRoutePrefix, profileRoutePrefix, routes} from "./routes/route-names.ts";
import MyOrganizations from "./routes/my-organizations.tsx";
import Navbar from "./components/navbar.tsx";

const router = createBrowserRouter([
    {
        path: routes.login,
        element: <Login />,
    },
    {
        path: routes.register,
        element: <Register />,
    },
    {
        path: "/",
        element: <Navigate to={routes.dashboard} />
    },
    {
        path: profileRoutePrefix,
        element: <PrivateRoute requiresOrganization={false} />,
        children: [
            {
                path: routes.selectOrg,
                element: <MyOrganizations />
            },
        ]
    },
    {
        path: appRoutePrefix,
        element: <PrivateRoute />,
        children: [
            {
                path: routes.dashboard,
                element: <Dashboard />
            },
        ]
    },
])

ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
      <div>
          <Navbar />
          <RouterProvider router={router}/>
      </div>
  </React.StrictMode>,
)
