<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-gray-100 to-gray-200 p-4">
        <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-200">
            <h2 class="text-3xl font-extrabold mb-6 text-center text-gray-800">Регистрация</h2>

            <!-- Индикатор шагов -->
            <div class="flex justify-between mb-6">
                <div :class="step >= 1 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-500'" class="flex-1 py-1 px-2 rounded-full text-center text-sm font-semibold mx-1">
                </div>
                <div :class="step >= 2 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-500'" class="flex-1 py-1 px-2 rounded-full text-center text-sm font-semibold mx-1">
                </div>
                <div :class="step >= 3 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-500'" class="flex-1 py-1 px-2 rounded-full text-center text-sm font-semibold mx-1">
                </div>
                <div :class="step >= 4 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-500'" class="flex-1 py-1 px-2 rounded-full text-center text-sm font-semibold mx-1">
                </div>
            </div>

            <!-- Шаги формы -->
            <div>
                <!-- Шаг 1 -->
                <div v-if="step === 1">
                    <label class="block mb-2 font-medium text-gray-700">Имя</label>
                    <input v-model="form.name" type="text" placeholder="Ваше имя"
                           class="w-full p-4 mb-4 border rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-gray-400 transition"/>

                    <label class="block mb-2 font-medium text-gray-700">Email</label>
                    <input v-model="form.email" type="email" placeholder="example@mail.com"
                           class="w-full p-4 mb-6 border rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-gray-400 transition"/>

                    <button @click="nextStep"
                            class="w-full py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl shadow hover:from-blue-600 hover:to-blue-700 transition">
                        Далее
                    </button>
                </div>

                <!-- Шаг 2 -->
                <div v-if="step === 2">
                    <label class="block mb-2 font-medium text-gray-700">Пароль</label>
                    <input v-model="form.password" type="password" placeholder="Пароль"
                           class="w-full p-4 mb-4 border rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-gray-400 transition"/>

                    <label class="block mb-2 font-medium text-gray-700">Подтверждение пароля</label>
                    <input v-model="form.password_confirmation" type="password" placeholder="Повторите пароль"
                           class="w-full p-4 mb-6 border rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-gray-400 transition"/>

                    <div class="flex justify-between">
                        <button @click="prevStep"
                                class="px-6 py-2 border rounded-xl text-gray-700 hover:bg-gray-100 transition">Назад</button>
                        <button @click="nextStep"
                                class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow hover:from-blue-600 hover:to-blue-700 transition">
                            Далее
                        </button>
                    </div>
                </div>

                <!-- Шаг 3 -->
                <div v-if="step === 3">
                    <label class="block mb-2 font-medium text-gray-700">Выберите роль</label>
                    <select v-model="form.role"
                            class="w-full p-4 mb-6 border rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                        <option value="">Выберите</option>
                        <option value="driver">Водитель</option>
                        <option value="passenger">Пассажир</option>
                    </select>

                    <div class="flex justify-between">
                        <button @click="prevStep"
                                class="px-6 py-2 border rounded-xl text-gray-700 hover:bg-gray-100 transition">Назад</button>
                        <button @click="nextStep"
                                class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow hover:from-blue-600 hover:to-blue-700 transition">
                            Далее
                        </button>
                    </div>
                </div>

                <!-- Шаг 4 -->
                <div v-if="step === 4">
                    <label class="block mb-2 font-medium text-gray-700">Номер телефона</label>
                    <input v-model="form.number" type="text" placeholder="998901234567"
                           class="w-full p-4 mb-4 border rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-gray-400 transition"/>

                    <button v-if="!codeSent" @click="sendCode"
                            class="w-full py-3 mb-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl shadow hover:from-blue-600 hover:to-blue-700 transition">
                        Отправить код
                    </button>

                    <div v-if="codeSent">
                        <label class="block mb-2 font-medium text-gray-700">Код SMS</label>
                        <input v-model="form.code" type="text" placeholder="1234"
                               class="w-full p-4 mb-6 border rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-gray-400 transition"/>

                        <div class="flex justify-between">
                            <button @click="prevStep"
                                    class="px-6 py-2 border rounded-xl text-gray-700 hover:bg-gray-100 transition">Назад</button>
                            <button @click="submit"
                                    class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow hover:from-green-600 hover:to-green-700 transition">
                                Зарегистрироваться
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-center text-sm text-gray-500 mt-6">
                Уже есть аккаунт?
                <a href="/login" class="text-blue-600 hover:text-blue-700 font-semibold">Войти</a>
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
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                role: '',
                number: '',
                code: '',
            }
        }
    },
    computed: {
        geoLat() {
            return this.getGeo().lat;
        },
        geoLng() {
            return this.getGeo().lng;
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
        sendCode() {
            if (!this.form.number) return
            this.api.post('/send-code', { number: this.form.number })
                .then(res => {
                    this.codeSent = true
                    this.code = res.data.code;
                });
        },
        async submit() {
            const geo = await this.getGeo()
            if (geo) {
                this.form.lat = geo.lat
                this.form.lng = geo.lng
            }

            this.api.post('/register', this.form)
                .then(res => {
                    if (res.data.success) {
                        localStorage.setItem('auth_token', res.data.token);
                        localStorage.setItem('userRole', res.data.user.role);
                        localStorage.setItem('userId', res.data.user.id);
                        this.$router.push('/');
                    } else {
                        console.error(res.data.error);
                    }
                });
        }
    }
}
</script>
