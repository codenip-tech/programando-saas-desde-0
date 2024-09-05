import {useEffect, useState} from "react";
import api from "../services/api.ts";

export function useProducts() {
    const [products, setProducts] = useState<{ id: number, name: string }[]>([])

    useEffect(() => {
        api.getProducts().then(({ products: apiProducts }) => {
            setProducts(apiProducts)
        })
    }, []);

    return {
        products,
    }
}
