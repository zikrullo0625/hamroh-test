<template>
    <div
        v-if="visible"
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 relative">
            <button
                @click="$emit('close')"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 transition"
            >
                ✕
            </button>

            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
                Обновить пользователя
            </h2>

            <form @submit.prevent="updateUser" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-300 focus:outline-none"
                        placeholder="Введите имя"
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-300 focus:outline-none"
                        placeholder="Введите email"
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-300 focus:outline-none"
                        placeholder="Введите новый пароль"
                        required
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition"
                >
                    Обновить
                </button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "UpdateUserModal",
    props: {
        user: {
            type: Object,
            required: true,
        },
        visible: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            form: {
                name: this.user?.name || "",
                email: this.user?.email || "",
                password: "",
            },
        }
    },
    methods: {
        async updateUser() {
            try {
                const response = await this.api.put(`/passengers/${this.user.id}`, this.form)
                this.$emit("updated", response.data.user)
                this.$emit("close")
            } catch (error) {
                console.error(error)
                alert("Ошибка при обновлении пользователя")
            }
        },
    },
}
</script>
