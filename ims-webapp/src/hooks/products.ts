import {useEffect, useState} from "react";
import api, {GetProductsInput} from "../services/api.ts";

export type Products = (Awaited<ReturnType<typeof api.getProducts>>['products'])
export type ProductsItem = Products[0]

export function useProducts() {
    const [products, setProducts] = useState<Products>([])
    const [sort, setSort] = useState<GetProductsInput['sort']>(null)
    const [filter, setFilter] = useState<GetProductsInput['filter']>(null)
    function sortBy(field: 'id' | 'name') {
        if (sort === null) {
            setSort({ field: field, direction: 'asc' })
        } else if (sort.direction === 'asc') {
            setSort({ field: field, direction: 'desc' })
        } else {
            setSort(null)
        }
    }

    function filterBy(field: 'name', value: string) {
        setFilter({ field: field, value: value })
    }

    async function importFile(file: File) {
        await api.importFile(file)
        refetchProducts()
    }

    function refetchProducts() {
        const filterValueLength = filter?.value.length ?? 0
        if (filterValueLength < 3 && filterValueLength > 0) {
            setProducts([])
        } else {
            api.getProducts({ sort: sort, filter: filterValueLength < 3 ? null : filter }).then(({ products: apiProducts }) => {
                setProducts(apiProducts)
            })
        }
    }

    useEffect(refetchProducts, [sort, filter]);

    return {
        products: products,
        sortBy: sortBy,
        filterBy: filterBy,
        importFile: importFile,
    }
}
