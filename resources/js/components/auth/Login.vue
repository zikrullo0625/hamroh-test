<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-gray-100 to-gray-200 p-4">
        <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-200">
            <h2 class="text-3xl font-extrabold mb-6 text-center text-gray-800">Вход</h2>
            <div>
                <div v-if="step === 1">
                    <label class="block mb-2 font-medium text-gray-700">Номер</label>
                    <input v-model="form.number" type="tel" placeholder="Ваш номер"
                           class="w-full p-4 mb-4 border rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-gray-400 transition"/>

                    <label class="block mb-2 font-medium text-gray-700">Пароль</label>
                    <input v-model="form.password" type="password" placeholder="Ваш пароль"
                           class="w-full p-4 mb-6 border rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-gray-400 transition"/>

                    <button @click="submit"
                            class="w-full py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl shadow hover:from-blue-600 hover:to-blue-700 transition">
                        Войти
                    </button>
                </div>
                </div>
            <p class="text-center text-sm text-gray-500 mt-6">
                Нету аккаунта?
                <a href="/register" class="text-blue-600 hover:text-blue-700 font-semibold">Зарегестрироваться</a>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            step: 1,
            codeSent: false,
            form: {
                password: '',
                number: '',
            }
        }
    },
    methods: {
        nextStep() {
            if (this.step < 4) this.step++
        },
        prevStep() {
            if (this.step > 1) {
                this.step--
                this.codeSent = false
            }
        },
        submit() {
            this.api.post('/login', this.form)
                .then(res => {
                        console.log(res.data)
                        localStorage.setItem('auth_token', res.data.token);
                        localStorage.setItem('userRole', res.data.user.role);
                        localStorage.setItem('userId', res.data.user.id);
                        this.$router.push('/');
                });
        }
    }
}
</script>
