<template>
    <div class="overflow-x-auto w-full">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created at</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated at</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
            <tr
                v-for="user in users"
                :key="user.id"
                class="hover:bg-gray-100 cursor-pointer"
                @click="goToProfile(user.id)"
            >
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.created_at }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.updated_at }}</td>
                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900 flex gap-x-2">
                    <button
                        @click.stop="openModal(user)"
                        class="rounded-md bg-amber-400 text-white px-2 py-1 hover:bg-amber-500"
                    >
                        Изменить
                    </button>
                    <button
                        @click.stop="deleteUser(user.id)"
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
            :user="selectedUser"
            :visible="showModal"
            @close="showModal = false"
            @updated="onUserUpdated"
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
            users: [],
            showModal: false,
            selectedUser: null,
        }
    },
    mounted() {
        this.getUsers()
    },
    methods: {
        goToProfile(id) {
            this.$router.push('/passengers/' + id);
        },
        getUsers() {
            this.api.get('/passengers').then((response) => {
                this.users = response.data.users
            })
        },
        openModal(user) {
            this.selectedUser = { ...user }
            this.showModal = true
        },
        onUserUpdated(updatedUser) {
            const index = this.users.findIndex(u => u.id === updatedUser.id)
            if (index !== -1) {
                this.users[index] = updatedUser
            }
        },
        deleteUser(id) {
            if (confirm('Удалить пользователя?')) {
                this.api.delete(`/passengers/${id}`).then(() => {
                    this.users = this.users.filter((u) => u.id !== id)
                })
            }
        },
    },
}
</script>
