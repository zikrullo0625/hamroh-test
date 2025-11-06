import { createRouter, createWebHistory } from 'vue-router'

import PassengersIndex from '../components/passengers/Index.vue'
import PassengersShow from '../components/passengers/Show.vue'

import DriversIndex from '../components/drivers/Index.vue'
import DriversShow from '../components/drivers/Show.vue'

import VehiclesIndex from '../components/vehicles/Index.vue'
import VehiclesShow from '../components/vehicles/Show.vue'

import RidesIndex from '../components/rides/Index.vue'
import RidesShow from '../components/rides/Show.vue'

import PaymentsIndex from '../components/payments/Index.vue'
import PaymentsShow from '../components/payments/Show.vue'

import RatingsIndex from '../components/ratings/Index.vue'
import RatingsShow from '../components/ratings/Show.vue'

const routes = [
    {
        path: '/passengers',
        children: [
            { path: '', component: PassengersIndex, name: 'passengers.index' },
            { path: ':id', component: PassengersShow, name: 'passengers.show' },
        ],
    },
    {
        path: '/drivers',
        children: [
            { path: '', component: DriversIndex, name: 'drivers.index' },
            { path: ':id', component: DriversShow, name: 'drivers.show' },
        ],
    },

    {
        path: '/vehicles',
        children: [
            { path: '', component: VehiclesIndex, name: 'vehicles.index' },
            { path: ':id', component: VehiclesShow, name: 'vehicles.show' },
        ],
    },

    {
        path: '/rides',
        children: [
            { path: '', component: RidesIndex, name: 'rides.index' },
            { path: ':id', component: RidesShow, name: 'rides.show' },
        ],
    },

    {
        path: '/payments',
        children: [
            { path: '', component: PaymentsIndex, name: 'payments.index' },
            { path: ':id', component: PaymentsShow, name: 'payments.show' },
        ],
    },

    {
        path: '/ratings',
        children: [
            { path: '', component: RatingsIndex, name: 'ratings.index' },
            { path: ':id', component: RatingsShow, name: 'ratings.show' },
        ],
    },

    { path: '/', redirect: '/users' },
]

export const router = createRouter({
    history: createWebHistory(),
    routes,
})
