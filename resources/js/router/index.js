import { createRouter, createWebHistory } from 'vue-router'

import UsersIndex from '../components/users/Index.vue'
import UsersShow from '../components/users/Show.vue'

const routes = [
    { path: '/users', component: UsersIndex },
    { path: '/users/:id', component: UsersShow },
]

export const router = createRouter({
    history: createWebHistory(),
    routes,
})
