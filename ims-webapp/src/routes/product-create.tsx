import api from "../services/api.ts";
import {useForm} from "../hooks/form.ts";
import {useState} from "react";
import {Navigate} from "react-router-dom";
import {routes} from "./route-names.ts";

function ProductCreate() {
    const [createdProductId, setCreatedProductId] = useState<number>()
    async function create() {
        const { id } = await api.createProduct({ name: values.name })
        reset()
        setCreatedProductId(id)
    }

    const { onSubmit, values, onChange, reset } = useForm(create, { name: '' })

    if (createdProductId) {
        return <Navigate to={routes.editProduct.replace(':productId', String(createdProductId))}></Navigate>
    }

    return (<>
        <form onSubmit={onSubmit} method="POST">
            <label htmlFor="name">
                Name:
                <input id="name" name="name" onChange={onChange} value={values.name}/>
            </label>
            <button type="submit">Create</button>
        </form>
    </>)
}

export default ProductCreate
