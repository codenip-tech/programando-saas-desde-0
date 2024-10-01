export const appRoutePrefix = '/app'
export const profileRoutePrefix = '/profile'

export const routes = {
    dashboard: `${appRoutePrefix}/dashboard`,
    products: `${appRoutePrefix}/products`,
    editProduct: `${appRoutePrefix}/products/:productId/edit`,
    createProduct: `${appRoutePrefix}/products/create`,
    login: '/login',
    register: '/register',
    selectOrg: `${profileRoutePrefix}/select-org`,
}
