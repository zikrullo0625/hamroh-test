<template>
    <div class="w-full max-h-[calc(100vh-100px)] overflow-y-auto overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
        <table class="min-w-[800px] w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10">
            <tr>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    From
                </th>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    To
                </th>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Distance
                </th>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Price
                </th>
                <th
                    class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Created at
                </th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
            <tr
                v-for="ride in rides"
                :key="ride.id"
                class="hover:bg-gray-100 cursor-pointer"
                @click="goToProfile(ride.id)"
            >
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ ride.from_address ?? 'Пусто' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ ride.to_address ?? 'Пусто' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ ride.distance + 'km' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ ride.price + 'tjs' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ new Date(ride.created_at).toLocaleString() }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import Update from "./Update.vue";
import CreateRide from "./CreateRide.vue";

export default {
    components: {CreateRide, Update },
    data() {
        return {
            statusTranslate: {
                created: "Создана",
                accepted: "Принята",
                in_progress: "В пути",
                completed: "Завершена",
                cancelled: "Отменена",
            },
            statusClasses: {
                created: "bg-gray-100 text-gray-800",
                accepted: "bg-blue-100 text-blue-800",
                in_progress: "bg-yellow-100 text-yellow-800",
                completed: "bg-green-100 text-green-800",
                cancelled: "bg-red-100 text-red-800",
            },
            rides: [],
            showModal: false,
            selectedRide: null
        };
    },
    computed: {
        userRole() {
            return  localStorage.getItem("userRole")
        },
    },
    mounted() {
        this.eventBus.on('ride-event', this.handleRide)

    },
    beforeUnmount() {
        this.eventBus.off('ride-event', this.handleRide)
    },
    methods: {
        handleRide(ride) {
            console.log('Ride Event:', ride);
            if (typeof this[ride._] === 'function') {
                this[ride._](ride);
            } else {
                console.warn(`Handler ${ride._} not found`, ride);
            }
        },
        goToProfile(id) {
            this.$router.push("/rides/" + id);
        },
        openModal(ride) {
            this.selectedRide = { ...ride };
            this.showModal = true;
        },
        onRideUpdated(updatedRide) {
            const index = this.rides.findIndex((u) => u.id === updatedRide.id);
            if (index !== -1) {
                this.rides[index] = updatedRide;
            }
        },
        deleteRide(ride) {
            this.rides = this.rides.filter((u) => u.id !== ride.id);
            console.log('Updated rides:', this.rides);
        },
        newRide(ride) {
            const exists = this.rides.some(r => r.id === ride.id);
            if (!exists) {
                this.rides.push(ride);
            }

        },
        formatDate(date) {
            return new Date(date).toLocaleString();
        },
    },
};
</script>
