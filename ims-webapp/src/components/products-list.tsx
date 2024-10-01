import {ProductsItem, useProducts} from "../hooks/products.ts";
import {Link} from "react-router-dom";
import {routes} from "../routes/route-names.ts";

function ProductRow({ product }: { product: ProductsItem }) {
    return <tr key={product.id}>
        <td><Link to={routes.editProduct.replace(':productId', String(product.id))}>{product.id}</Link></td>
        <td>{product.name}</td>
    </tr>
}

function ProductsList() {
    const {products} = useProducts()

    return (
        <>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                {products.map(product => <ProductRow product={product}></ProductRow>)}
                </tbody>
            </table>
            <ul>

            </ul>
        </>
    )
}

export default ProductsList
