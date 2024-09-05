import {Navigate, Outlet, useLocation} from "react-router-dom";
import {useSelectedOrganization} from "../hooks/selected-organization.ts";
import {routes} from "./route-names.ts";

function PrivateRoute (input: { requiresOrganization?: boolean }) {
    const { requiresOrganization } = Object.assign({ requiresOrganization: true }, input)
    const location = useLocation();
    /**
     * @todo use state provider
     * @todo Check if jwt is expired
     */
    const token = localStorage.getItem('token')
    const { getSelectedOrganization } = useSelectedOrganization()


    if (requiresOrganization && !getSelectedOrganization()) {
        return <Navigate to={routes.selectOrg} replace state={{ from: location }} />
    }

    return token
        ? <Outlet />
        : <Navigate to={routes.login} replace state={{ from: location }} />
}

export default PrivateRoute
