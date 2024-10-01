import {useEffect, useState} from "react";
import api from "../services/api.ts";
import {useParams} from "react-router-dom";
import {useForm} from "../hooks/form.ts";

type Product = Awaited<ReturnType<typeof api.getProduct>>['product']
type Tags = Awaited<ReturnType<typeof api.getTags>>['tags']
type CreateTagFunction = (input: { name: string }) => Promise<void>

function ProductForm({ product, tags, createTag }: { product: Product, tags: Tags, createTag: CreateTagFunction }) {
    async function update() {
        await api.updateProduct({ id: product.id, name: values.name, tagIds: values.tags.map(Number) })
    }

    const { onSubmit, values, onChange, onSelectChange } = useForm(update, { name: product.name, tags: product.tagIds })
    const [newTagName, setNewTagName] = useState('')

    return <>
        <form onSubmit={onSubmit} method="POST">
            <label htmlFor="name">
                Name:
                <input id="name" name="name" onChange={onChange} value={values.name}/>
            </label>
            <label htmlFor="tags">
                Tags:
                <select onChange={onSelectChange} name="tags" value={values.tags.map(String)} multiple>
                    {tags.map(t => <option value={t.id}>{t.name}</option>)}
                </select>
            </label>
            Or create tag
            <input id="tag" onChange={(event) => setNewTagName(event.target.value)} value={newTagName} />
            <button disabled={!newTagName} onClick={() => { createTag({ name: newTagName }); setNewTagName('') }}>Create tag</button>
            <button type="submit">Update</button>
        </form>
    </>
}

function ProductFormOrLoading({ product, tags, createTag }: { product: Product | undefined, tags: Tags | undefined, createTag: CreateTagFunction }) {
    return product && tags ? <ProductForm product={product} tags={tags} createTag={createTag} /> : <>Loading</>
}

function ProductEdit() {
    const { productId: productIdString } = useParams<{ productId: string }>()
    const productId = Number(productIdString)

    const [product, setProduct] = useState<Product>()
    const [tags, setTags] = useState<Tags>()

    async function fetchTags() {
        const { tags: _tags } = await api.getTags()
        setTags(_tags)
    }

    async function createTag({ name }: { name: string }) {
        await api.createTag({ name: name })
        await fetchTags()
    }

    useEffect(() => {
        (async function() {
            const { product: _product } = await api.getProduct(Number(productId))
            setProduct(_product)
        })();
        fetchTags()
    }, []);

    return (
        <>
            <h1>Edit product</h1>

            <ProductFormOrLoading product={product} tags={tags} createTag={createTag} />
        </>
    )
}

export default ProductEdit
