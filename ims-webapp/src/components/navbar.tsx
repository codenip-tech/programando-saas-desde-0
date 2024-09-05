import {routes} from "../routes/route-names.ts";
import {useSelectedOrganization} from "../hooks/selected-organization.ts";

function Navbar() {
    const { getSelectedOrganization } = useSelectedOrganization()
    console.log('test')
    return (
        <>
            <nav>
                <h2>Title</h2>
                <ul>
                    <li><a href={routes.dashboard}>Dashboard</a></li>
                </ul>
                <div>
                    {getSelectedOrganization()?.name ?? 'No organization selected'}
                </div>
            </nav>
        </>
    )
}

export default Navbar
