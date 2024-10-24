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


    async function exportToCsv() {
        const exportContent = await api.exportProducts(buildGetProductsInput())
        const url = window.URL.createObjectURL(exportContent);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = 'export.csv';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a)
    }

    async function importFile(file: File) {
        await api.importFile(file)
        refetchProducts()
    }

    function buildGetProductsInput() {
        const filterValueLength = filter?.value.length ?? 0
        return { sort: sort, filter: filterValueLength < 3 ? null : filter }
    }

    function refetchProducts() {
        // @todo Check why it's called 3 times on load
        const filterValueLength = filter?.value.length ?? 0
        if (filterValueLength < 3 && filterValueLength > 0) {
            setProducts([])
        } else {
            api.getProducts(buildGetProductsInput()).then(({ products: apiProducts }) => {
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
        exportToCsv: exportToCsv,
    }
}
