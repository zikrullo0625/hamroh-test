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
                    Number
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
                v-for="driver in drivers"
                :key="driver.id"
                class="hover:bg-gray-100 cursor-pointer"
                @click="goToProfile(driver.id)"
            >
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ driver.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ driver.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ driver.number }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ new Date(driver.created_at).toLocaleString() }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ new Date(driver.updated_at).toLocaleString() }}
                </td>
                <td
                    class="px-3 py-3 whitespace-nowrap text-sm text-gray-900 flex gap-x-2"
                >
                    <button
                        @click.stop="openModal(driver)"
                        class="rounded-md bg-amber-400 text-white px-2 py-1 hover:bg-amber-500"
                    >
                        Изменить
                    </button>
                    <button
                        @click.stop="deleteDriver(driver.id)"
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
            :driver="selectedDriver"
            :visible="showModal"
            @close="showModal = false"
            @updated="onDriverUpdated"
        />
    </div>
</template>

<script>
import Update from "./Update.vue";

export default {
    components: { Update },
    data() {
        return {
            drivers: [],
            showModal: false,
            selectedDriver: null,
        };
    },
    mounted() {
        this.getDrivers();
    },
    methods: {
        goToProfile(id) {
            this.$router.push("/drivers/" + id);
        },
        getDrivers() {
            this.api.get("/drivers").then((response) => {
                this.drivers = response.data.drivers;
            });
        },
        openModal(driver) {
            this.selectedDriver = { ...driver };
            this.showModal = true;
        },
        onDriverUpdated(updatedDriver) {
            const index = this.drivers.findIndex((u) => u.id === updatedDriver.id);
            if (index !== -1) {
                this.drivers[index] = updatedDriver;
            }
        },
        deleteDriver(id) {
            if (confirm("Удалить водителя?")) {
                this.api.delete(`/drivers/${id}`).then(() => {
                    this.drivers = this.drivers.filter((u) => u.id !== id);
                });
            }
        },
        formatDate(date) {
            return new Date(date).toLocaleString();
        },
    },
};
</script>
