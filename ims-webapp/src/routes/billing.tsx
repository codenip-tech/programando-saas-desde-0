import api from "../services/api.ts";
import {useSearchParams} from "react-router-dom";
import {useEffect} from "react";

const checkoutSuccessQueryParamKey = 'checkoutSuccess'

function Billing() {
    async function goToPortal() {
        window.location.href = await api.getPortalUrl()
    }
    const [searchParams, setSearchParams] = useSearchParams();

    useEffect(() => {

        console.log('i fire once');
        if (!searchParams.has(checkoutSuccessQueryParamKey)) return
        api.onCheckoutSuccess().then((success) => {
            if (!success) return
            searchParams.delete(checkoutSuccessQueryParamKey)
            setSearchParams(searchParams)
        })
    }, []);


    return (
        <>
            <h1>Billing</h1>
            <button onClick={goToPortal}>Go to portal</button>
        </>
    )
}

export default Billing
