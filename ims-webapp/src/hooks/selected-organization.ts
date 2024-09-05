import selectedOrganizationStorage from "../services/selected-organization-storage.ts";


export function useSelectedOrganization() {
    // @todo How to use useEffect and useState? When we used, right after switching the org it was still null
    return {
        setSelectedOrganization: selectedOrganizationStorage.setOrg,
        getSelectedOrganization: () => selectedOrganizationStorage.getOrg(),
    }
}
