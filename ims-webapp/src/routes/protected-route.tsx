import {Navigate, Outlet, useLocation} from "react-router-dom";
import {useSelectedOrganization} from "../hooks/selected-organization.ts";
import {routes} from "./route-names.ts";
import * as jose from 'jose'

function getToken() {
    const token = localStorage.getItem('token')
    if (!token) {
        return null
    }
    const claims = jose.decodeJwt<{ exp: number }>(token)
    if (claims.exp * 1000 > Date.now()) {
        return token
    }
    localStorage.removeItem('token')
    return null
}

function PrivateRoute (input: { requiresOrganization?: boolean }) {
    const { requiresOrganization } = Object.assign({ requiresOrganization: true }, input)
    const location = useLocation();
    /**
     * @todo use state provider
     */
    const token = getToken()

    const { getSelectedOrganization } = useSelectedOrganization()

    if (requiresOrganization && !getSelectedOrganization()) {
        return <Navigate to={routes.selectOrg} replace state={{ from: location }} />
    }

    return token
        ? <Outlet />
        : <Navigate to={routes.login} replace state={{ from: location }} />
}

export default PrivateRoute
