import {Navigate, Outlet, useLocation} from "react-router-dom";

function PrivateRoute () {
    const location = useLocation();
    /**
     * @todo use state provider
     * @todo Check if jwt is expired
     */
    const token = localStorage.getItem('token')

    return token
        ? <Outlet />
        : <Navigate to="/login" replace state={{ from: location }} />
}

export default PrivateRoute
