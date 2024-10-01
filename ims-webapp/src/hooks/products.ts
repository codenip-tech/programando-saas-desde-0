import {useEffect, useState} from "react";
import api from "../services/api.ts";

export type Products = (Awaited<ReturnType<typeof api.getProducts>>['products'])
export type ProductsItem = Products[0]

export function useProducts() {
    const [products, setProducts] = useState<Products>([])

    useEffect(() => {
        api.getProducts().then(({ products: apiProducts }) => {
            setProducts(apiProducts)
        })
    }, []);

    return {
        products,
    }
}
