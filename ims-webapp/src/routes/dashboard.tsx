import {useProducts} from "../hooks/products.ts";

function Dashboard() {
    const { products } = useProducts()

    return (
        <>
            <h1>Dashboard</h1>
            <ul>
                {products.map(product => <li key={product.id}>{product.name}</li>)}
            </ul>
        </>
    )
}

export default Dashboard
