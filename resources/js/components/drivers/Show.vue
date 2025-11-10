<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Данные водителя</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-800">
                <div>
                    <span class="font-medium">ID:</span>
                    <span>{{ driver.id }}</span>
                </div>
                <div>
                    <span class="font-medium">Имя:</span>
                    <span>{{ driver.name }}</span>
                </div>
                <div>
                    <span class="font-medium">Email:</span>
                    <span>{{ driver.email }}</span>
                </div>
                <div>
                    <span class="font-medium">Роль:</span>
                    <span>{{ driver.role }}</span>
                </div>
                <div>
                    <span class="font-medium">Создан:</span>
                    <span>{{ driver.created_at }}</span>
                </div>
                <div>
                    <span class="font-medium">Обновлён:</span>
                    <span>{{ driver.updated_at }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
      return {
          driver: []
      }
    },
    methods: {
        getDriver(id) {
            this.api.get('/drivers/' + id)
                .then((response) => {
                    this.driver = response.data.driver
                })
        }
    },
    mounted() {
        this.getDriver(this.$route.params.id)
    }
}
</script>
