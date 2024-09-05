import api from "../services/api.ts";
import {useEffect, useState} from "react";
import {useSelectedOrganization} from "../hooks/selected-organization.ts";
import {Navigate} from "react-router-dom";
import {routes} from "./route-names.ts";

type ApiOrganizations = Awaited<ReturnType<typeof api.getMyOrganizations>>['organizations']
type ApiOrganization = ApiOrganizations[0]

function MyOrganizations() {
    const [myOrganizations, setMyOrganizations] = useState<ApiOrganizations>([])
    const [toDashboard, setToDashboard] = useState(false)
    const { setSelectedOrganization: _setSelectOrganization } = useSelectedOrganization()
    useEffect(() => {
        api.getMyOrganizations().then(({ organizations }) => {
            setMyOrganizations(organizations)
        })
    }, []);


    if (toDashboard) {
        return <Navigate to={routes.dashboard} />
    }

    function setSelectedOrganization(org: ApiOrganization) {
        _setSelectOrganization(org)
        setToDashboard(true)
    }

    return (
        <>
            <h1>Select an organization</h1>
            {myOrganizations.map(org => <button onClick={() => setSelectedOrganization(org)} key={org.id}>{org.name}</button>)}
        </>
    )
}

export default MyOrganizations
