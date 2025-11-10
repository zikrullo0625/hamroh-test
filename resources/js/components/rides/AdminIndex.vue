<template>
    <CreateRide></CreateRide>
    <div class="w-full max-h-[calc(100vh-100px)] overflow-y-auto overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
        <table class="min-w-[800px] w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10">
            <tr>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    ID
                </th>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Driver Name
                </th>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Car
                </th>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Car Number
                </th>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Car Color
                </th>
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
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
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
                    {{ ride.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ ride.driver?.name ?? 'Пусто' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ ride.driver?.vehicle ? ride.driver.vehicle.brand + ' ' + ride.driver.vehicle.model : 'Пусто' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ ride.driver?.vehicle?.number ?? 'Пусто' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ ride.driver?.vehicle?.color ?? 'Пусто' }}
                </td>
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

        <Update
            v-if="showModal"
            :ride="selectedRide"
            :visible="showModal"
            @close="showModal = false"
            @updated="onRideUpdated"
        />
    </div>
</template>

<script>
import Update from "./Update.vue";
import CreateRide from "./CreateRide.vue";

export default {
    components: {CreateRide, Update },
    data() {
        return {
            rides: [],
            showModal: false,
            selectedRide: null,
        };
    },
    mounted() {
        this.getRides();
    },
    methods: {
        goToProfile(id) {
            this.$router.push("/rides/" + id);
        },
        getRides() {
            this.api.get("/rides").then((response) => {
                this.rides = response.data.rides;
            });
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
        deleteRide(id) {
            if (confirm("Отменить поезду?")) {
                this.api.post(`/rides/cancel/${id}`).then(() => {
                    this.rides = this.rides.filter((u) => u.id !== id);
                });
            }
        },
        formatDate(date) {
            return new Date(date).toLocaleString();
        },
    },
};
</script>
