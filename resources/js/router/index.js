import { createMemoryHistory, createRouter } from 'vue-router'

import Users from '../components/users/Index.vue'

const routes = [
    { path: '/', component: Users },
]

export const router = createRouter({
    history: createMemoryHistory(),
    routes,
})
