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
                v-for="passenger in passengers"
                :key="passenger.id"
                class="hover:bg-gray-100 cursor-pointer"
                @click="goToProfile(passenger.id)"
            >
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ passenger.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ passenger.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ passenger.number }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ new Date(passenger.created_at).toLocaleString() }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ new Date(passenger.updated_at).toLocaleString() }}
                </td>
                <td
                    class="px-3 py-3 whitespace-nowrap text-sm text-gray-900 flex gap-x-2"
                >
                    <button
                        @click.stop="openModal(passenger)"
                        class="rounded-md bg-amber-400 text-white px-2 py-1 hover:bg-amber-500"
                    >
                        Изменить
                    </button>
                    <button
                        @click.stop="deletePassenger(passenger.id)"
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
            :passenger="selectedPassenger"
            :visible="showModal"
            @close="showModal = false"
            @updated="onPassengerUpdated"
        />
    </div>
</template>

<script>
import axios from 'axios'
import Update from './Update.vue'

export default {
    components: { Update },
    data() {
        return {
            passengers: [],
            showModal: false,
            selectedPassenger: null,
        }
    },
    mounted() {
        this.getPassengers()
    },
    methods: {
        goToProfile(id) {
            this.$router.push('/passengers/' + id);
        },
        getPassengers() {
            this.api.get('/passengers').then((response) => {
                this.passengers = response.data.passengers
            })
        },
        openModal(passenger) {
            this.selectedPassenger = { ...passenger }
            this.showModal = true
        },
        onPassengerUpdated(updatedPassenger) {
            const index = this.passengers.findIndex(u => u.id === updatedPassenger.id)
            if (index !== -1) {
                this.passengers[index] = updatedPassenger
            }
        },
        deletePassenger(id) {
            if (confirm('Удалить пользователя?')) {
                this.api.delete(`/passengers/${id}`).then(() => {
                    this.passengers = this.passengers.filter((u) => u.id !== id)
                })
            }
        },
    },
}
</script>
