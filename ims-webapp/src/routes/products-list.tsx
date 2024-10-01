import ProductsList from "../components/products-list.tsx";
import {Link} from "react-router-dom";
import {routes} from "./route-names.ts";

function Dashboard() {
    return (
        <>
            <h1>Products</h1>
            <ProductsList />
            <Link to={routes.createProduct}>Create</Link>
        </>
    )
}

export default Dashboard
