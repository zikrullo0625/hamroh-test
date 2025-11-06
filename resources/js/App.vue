<template>
    <div class="flex min-h-screen bg-gray-50 text-gray-800">
        <aside
            class="w-64 bg-white shadow-xl flex flex-col border-r border-gray-200 transition-all duration-300"
        >
            <div
                class="p-5 text-2xl font-extrabold border-b border-gray-200 text-gray-700 tracking-wide"
            >
                Hamroh Taxi
            </div>

            <nav class="flex-1 p-4 space-y-1">
                <RouterLink
                    v-for="link in links"
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

        <main class="flex-1 p-6 transition-all duration-300">
            <router-view />
        </main>
    </div>
</template>

<script setup>
const links = [
    { label: "Drivers", to: "/drivers", icon: "fas fa-taxi" },
    { label: "Passengers", to: "/passengers", icon: "fas fa-users" },
    { label: "Vehicles", to: "/vehicles", icon: "fas fa-car" },
    { label: "Rides", to: "/rides", icon: "fas fa-route" },
    { label: "Payments", to: "/payments", icon: "fas fa-credit-card" },
    { label: "Ratings", to: "/ratings", icon: "fas fa-star" },
];
</script>

