import { createRouter, createWebHistory } from 'vue-router'
import FrontOffice from '../views/FrontOffice.vue'
import Login from '../views/BackOffice/Login.vue'
import Dashboard from '../views/BackOffice/Dashboard.vue'
import Interventions from '../views/BackOffice/Interventions.vue'

const routes = [
    {
        path: '/',
        name: 'FrontOffice',
        component: FrontOffice
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/admin',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/interventions',
        name: 'Interventions',
        component: Interventions,
        meta: { requiresAuth: true }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('isLoggedIn') === 'true'
    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login')
    } else {
        next()
    }
})

export default router
