import { createRouter, createWebHistory } from 'vue-router'

import PassengersIndex from '../components/passengers/Index.vue'
import PassengersShow from '../components/passengers/Show.vue'

import DriversIndex from '../components/drivers/Index.vue'
import DriversShow from '../components/drivers/Show.vue'

import VehiclesIndex from '../components/vehicles/Index.vue'
import VehiclesShow from '../components/vehicles/Show.vue'

import RidesAdminIndex from '../components/rides/AdminIndex.vue'
import RidesDriverIndex from '../components/rides/DriverIndex.vue'
import RidesShow from '../components/rides/Show.vue'

import PaymentsIndex from '../components/payments/Index.vue'
import PaymentsShow from '../components/payments/Show.vue'

import RatingsIndex from '../components/ratings/Index.vue'
import RatingsShow from '../components/ratings/Show.vue'

import Register from '../components/auth/Register.vue'
import Login from '../components/auth/Login.vue'
import CreateRide from "../components/rides/CreateRide.vue";

let ridesIndex = RidesAdminIndex
let ridesShow = RidesShow

const userRole = localStorage.getItem('userRole')

if (userRole === 'admin') {
    ridesIndex = RidesAdminIndex
} else {
    ridesIndex = RidesDriverIndex
}

console.log(userRole)

const routes = [
    { path: '/register', component: Register, name: 'register' },
    { path: '/login', component: Login, name: 'login' },

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
            { path: '', component: ridesIndex, name: 'rides.index' },
            { path: ':id', component: ridesShow, name: 'rides.show' },
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

    { path: '/', redirect: '/rides' },

    {
        path: '/actual',
        name: 'ActualRides',
        component: RidesShow,
        props: { actual: true }
    },
    {
        path: '/order-taxi',
        name: 'OrderTaxi',
        component: CreateRide,
    }
]

export const router = createRouter({
    history: createWebHistory(),
    routes,
})
