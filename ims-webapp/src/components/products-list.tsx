import {ProductsItem, useProducts} from "../hooks/products.ts";
import {Link} from "react-router-dom";
import {routes} from "../routes/route-names.ts";
import {useEffect, useState} from "react";

function ProductRow({ product }: { product: ProductsItem }) {
    return <tr key={product.id}>
        <td><Link to={routes.editProduct.replace(':productId', String(product.id))}>{product.id}</Link></td>
        <td>{product.name}</td>
    </tr>
}

function ProductsList() {
    const {products, sortBy, filterBy, importFile} = useProducts()
    const [filterValue, setFilterValue] = useState('')

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files) {
            importFile(e.target.files[0])
        }
    };

    useEffect(() => {
        filterBy('name', filterValue)
    }, [filterValue]);

    return (
        <>
            <input id="file" type="file" onChange={handleFileChange}/>
            <input value={filterValue} onChange={(event) => setFilterValue(event.target.value)} />
            <table>
                <thead>
                <tr>
                    <th><a onClick={() => sortBy('id')}>ID</a></th>
                    <th><a onClick={() => sortBy('name')}>Name</a></th>
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
