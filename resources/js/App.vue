<template>
    <div class="flex h-screen bg-gray-50 text-gray-800 overflow-hidden">
        <aside
            v-if="showSideBar"
            class="w-64 bg-white shadow-xl flex flex-col border-r border-gray-200 transition-all duration-300"
        >
            <div
                class="p-5 text-2xl font-extrabold border-b border-gray-200 text-gray-700 tracking-wide"
            >
                Hamroh Taxi
            </div>

            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <RouterLink
                    v-for="link in links.filter(l => l.show)"
                    :key="link.to"
                    :to="link.to"
                    class="flex items-center gap-3 py-2.5 px-4 rounded-xl"
                    active-class="bg-blue-500 text-white shadow-md"
                >
                    <i :class="link.icon" class="w-5 text-lg"></i>
                    <span class="font-medium">{{ link.label }}</span>
                </RouterLink>
            </nav>
        </aside>

        <main class="flex-1 p-2 overflow-y-auto">
            <router-view/>
        </main>
    </div>
</template>

<script>
export default {
    data() {
        return {
        }
    },
    mounted() {
        this.initializeWebsocket();
        console.log(this.userId);
        this.eventBus.emit('new-message', 'Привет, мир!')
    },
    methods: {
      initializeWebsocket() {
          const channel = window.Echo.channel('rides');

          channel.listen('RideEvent', (e) => {
              console.log('RideEvent', e);
              this.eventBus.emit('ride-event', e.ride)
          });
      },
    },
    computed: {
        userRole() {
            return localStorage.getItem("userRole")
        },
        userId() {
            return localStorage.getItem("userId")
        },
        showSideBar() {
            return this.$route.path !== '/login' && this.$route.path !== '/register'
        },
        links() {
            const role = this.userRole;
            return [
                {label: "Order a taxi", to: "/order-taxi", icon: "fas fa-plus", show: role === 'admin' || role === 'passenger' },
                {label: "In progress", to: "/actual", icon: "fas fa-gauge-high", show: role === 'driver' || role === 'passenger' },
                {label: "Drivers", to: "/drivers", icon: "fas fa-taxi", show: role === 'admin'},
                {label: "Passengers", to: "/passengers", icon: "fas fa-users", show: role === 'admin'},
                {label: "Vehicles", to: "/vehicles", icon: "fas fa-car", show: role === 'admin'},
                {label: "Rides", to: "/rides", icon: "fas fa-route", show: true},
                {label: "Payments", to: "/payments", icon: "fas fa-credit-card", show: true},
                {label: "Ratings", to: "/ratings", icon: "fas fa-star", show: true},
            ];
        }
    }
}
</script>

