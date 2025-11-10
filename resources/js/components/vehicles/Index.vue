<template>
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
                    Name
                </th>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Email
                </th>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Created at
                </th>
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Updated at
                </th>
                <th
                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Actions
                </th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
            <tr
                v-for="vehicle in vehicles"
                :key="vehicle.id"
                class="hover:bg-gray-100 cursor-pointer"
                @click="goToProfile(vehicle.id)"
            >
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ vehicle.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ vehicle.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ vehicle.email }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ new Date(vehicle.created_at).toLocaleString() }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ new Date(vehicle.updated_at).toLocaleString() }}
                </td>
                <td
                    class="px-3 py-3 whitespace-nowrap text-sm text-gray-900 flex gap-x-2"
                >
                    <button
                        @click.stop="openModal(vehicle)"
                        class="rounded-md bg-amber-400 text-white px-2 py-1 hover:bg-amber-500"
                    >
                        Изменить
                    </button>
                    <button
                        @click.stop="deleteVehicle(vehicle.id)"
                        class="rounded-md bg-red-500 text-white px-2 py-1 hover:bg-red-600"
                    >
                        Удалить
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <Update
            v-if="showModal"
            :vehicle="selectedVehicle"
            :visible="showModal"
            @close="showModal = false"
            @updated="onVehicleUpdated"
        />
    </div>
</template>

<script>
import Update from "./Update.vue";

export default {
    components: { Update },
    data() {
        return {
            vehicles: [],
            showModal: false,
            selectedVehicle: null,
        };
    },
    mounted() {
        this.getVehicles();
    },
    methods: {
        goToProfile(id) {
            this.$router.push("/vehicles/" + id);
        },
        getVehicles() {
            this.api.get("/vehicles").then((response) => {
                this.vehicles = response.data.vehicles;
            });
        },
        openModal(vehicle) {
            this.selectedVehicle = { ...vehicle };
            this.showModal = true;
        },
        onVehicleUpdated(updatedVehicle) {
            const index = this.vehicles.findIndex((u) => u.id === updatedVehicle.id);
            if (index !== -1) {
                this.vehicles[index] = updatedVehicle;
            }
        },
        deleteVehicle(id) {
            if (confirm("Удалить водителя?")) {
                this.api.delete(`/vehicles/${id}`).then(() => {
                    this.vehicles = this.vehicles.filter((u) => u.id !== id);
                });
            }
        },
        formatDate(date) {
            return new Date(date).toLocaleString();
        },
    },
};
</script>
