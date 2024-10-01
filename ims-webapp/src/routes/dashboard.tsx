import {Link} from "react-router-dom";
import {routes} from "./route-names.ts";

function Dashboard() {
    return (
        <>
            <h1>Dashboard</h1>
            <ul>
                <li><Link to={routes.products}>Products</Link></li>
            </ul>


        </>
    )
}

export default Dashboard
