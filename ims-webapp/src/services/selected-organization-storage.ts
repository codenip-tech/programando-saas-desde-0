export type SelectedOrganization = { id: number, name: string }

class SelectedOrganizationStorage {
    public getOrg(): SelectedOrganization | null {
        const selectedOrganization = localStorage.getItem('selected_organization')

        return selectedOrganization ? JSON.parse(selectedOrganization) : null
    }

    public setOrg(organization: SelectedOrganization) {
        localStorage.setItem('selected_organization', JSON.stringify(organization))
    }
}

export default new SelectedOrganizationStorage()
