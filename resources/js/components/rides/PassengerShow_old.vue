<template>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <button @click="goBack" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition">
                        <span class="text-xl"><i class="fas fa-arrow-left"></i></span>
                    </button>
                    <h1 class="text-3xl font-bold text-gray-900">Поездка #{{ ride?.id }}</h1>
                </div>
                <div class="flex items-center gap-3">
                    <span :class="statusClasses[ride?.status.name] || 'bg-gray-100 text-gray-800'" class="px-4 py-2 rounded-full text-sm font-semibold">
                        {{ statusTranslate[ride?.status.name] || 'Неизвестно' }}
                    </span>
                </div>
            </div>

            <div v-if="!ride" class="flex items-center justify-center py-20">
                <div class="text-center">
                    <p class="text-gray-600">Загрузка...</p>
                </div>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <l-map
                            v-if="mapReady"
                            v-model:zoom="zoom"
                            :center="center"
                            :useGlobalLeaflet="true"
                            style="height: 450px; width: 100%;"
                        >
                            <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"></l-tile-layer>
                            <l-marker v-if="fromCoords" :lat-lng="fromCoords" :icon="startIcon">
                                <l-popup>Точка посадки<br/>{{ ride.from_address || 'Адрес не указан' }}</l-popup>
                            </l-marker>
                            <l-marker v-if="toCoords" :lat-lng="toCoords" :icon="endIcon">
                                <l-popup>Точка высадки<br/>{{ ride.to_address || 'Адрес не указан' }}</l-popup>
                            </l-marker>
                            <l-polyline v-if="routeCoords.length" :lat-lngs="routeCoords" color="#3b82f6" :weight="5" :opacity="0.7" />
                        </l-map>
                        <div v-else class="h-[450px] flex items-center justify-center bg-gray-100">
                            <p class="text-gray-500">Загрузка карты...</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Данные поездки</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-coins"></i>
                                        Стоимость
                                    </p>
                                    <p class="text-2xl font-bold text-gray-900">{{ ride.price }} TJS</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-route"></i>
                                        Расстояние
                                    </p>
                                    <p class="text-2xl font-bold text-gray-900">{{ ride.distance }} км</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-clock"></i>
                                        Длительность
                                    </p>
                                    <p class="text-2xl font-bold text-gray-900">{{ ride.duration || '—' }} мин</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4"><i class="fa-solid fa-table"></i> Дополнительно</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600">Адресс посадки:</span>
                                <span class="font-mono font-medium text-gray-900">{{ ride.from_address }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600">Адресс высадки:</span>
                                <span class="font-mono font-medium text-gray-900">{{ ride.to_address }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600">Способ оплаты:</span>
                                <span class="font-medium text-gray-900">{{ ride.payment_method ?? 'Пусто' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Оценка:</span>
                                <span class="font-medium text-gray-900">{{ ride.rating?.score ?? 'Пусто' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <button v-if="ride.status.name !== 'completed' && ride.status.name !== 'cancelled'" @click="cancelRide" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-lg transition">
                            Отменить поездку
                        </button>

                        <button v-if="ride.status.name !== 'completed' && ride.status.name !== 'cancelled'" @click="cancelRide" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-lg transition">
                            Отменить поездку
                        </button>

                        <div v-if="ride.status.name === 'completed' || ride.status.name === 'cancelled'" class="mt-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Оставить отзыв</h2>
                            <textarea v-model="review" placeholder="Ваш отзыв..." class="w-full p-3 border border-gray-200 rounded-lg resize-none"></textarea>
                            <button @click="submitReview" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">Отправить</button>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Временная линия</h2>
                        <div class="space-y-4.5">
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600 font-medium">Создана:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ formatDate(ride.statuses.find(s => s.name === 'created')?.created_at) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600 font-medium">Принята:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ formatDate(ride.statuses.find(s => s.name === 'accepted')?.created_at) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600 font-medium">Начата:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ formatDate(ride.statuses.find(s => s.name === 'in_progress')?.created_at) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600 font-medium">Завершена:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ formatDate(ride.statuses.find(s => s.name === 'completed')?.created_at) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Отменена:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ formatDate(ride.statuses.find(s => s.name === 'cancelled')?.created_at) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div v-if="ride.passenger || ride.driver" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Участники поездки</h2>
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            <div v-if="ride.passenger" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 bg-gray-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                        {{ ride.passenger?.name?.charAt(0) || 'П' }}
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-medium">Пассажир</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ ride.passenger?.name || '—' }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <p><i class="fa-solid fa-phone"></i>{{ ride.passenger?.number || '—' }}</p>
                                </div>
                            </div>
                            <div v-if="ride.driver" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 bg-gray-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                        {{ ride.driver?.name?.charAt(0) || 'В' }}
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-medium">Водитель</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ ride.driver?.name || '—' }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <p><i class="fa-solid fa-phone"></i>{{ ride.driver?.number || '—' }}</p>
                                    <p v-if="ride.driver?.vehicle"><i class="fa-solid fa-car"></i>{{ ride.driver.vehicle?.brand }} {{ ride.driver.vehicle?.model }} - {{ ride.driver.vehicle?.number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import "leaflet/dist/leaflet.css";
import { LMap, LTileLayer, LMarker, LPolyline, LPopup } from "@vue-leaflet/vue-leaflet";
import L from "leaflet";
import { useRoute } from "vue-router";

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png",
    iconUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
    shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
});

export default {
    props: ['actual'],
    components: { LMap, LTileLayer, LMarker, LPolyline, LPopup },
    data() {
        return {
            ride: null,
            zoom: 13,
            center: [38.5598, 68.7738],
            mapReady: false,
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
            startIcon: new L.Icon({
                iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png",
                shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41],
            }),
            endIcon: new L.Icon({
                iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png",
                shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41],
            }),
            review: '',
        };
    },
    computed: {
        fromCoords() {
            if (!this.ride?.from) return null;
            try {
                const coords = typeof this.ride.from === 'string' ? JSON.parse(this.ride.from) : this.ride.from;
                if (coords[0] && coords[1]) return [coords[0], coords[1]];
            } catch (e) {}
            return null;
        },
        toCoords() {
            if (!this.ride?.to) return null;
            try {
                const coords = typeof this.ride.to === 'string' ? JSON.parse(this.ride.to) : this.ride.to;
                if (coords[0] && coords[1]) return [coords[0], coords[1]];
            } catch (e) {}
            return null;
        },
        routeCoords() {
            if (!this.ride?.geometry) {
                if (this.fromCoords && this.toCoords) return [this.fromCoords, this.toCoords];
                return [];
            }
            try {
                let geometry = typeof this.ride.geometry === 'string' ? JSON.parse(this.ride.geometry) : this.ride.geometry;
                if (Array.isArray(geometry)) return geometry;
                if (geometry.coordinates && Array.isArray(geometry.coordinates)) return geometry.coordinates;
            } catch (e) {}
            if (this.fromCoords && this.toCoords) return [this.fromCoords, this.toCoords];
            return [];
        }
    },
    async mounted() {
        const route = useRoute();
        const id = route.params.id;
        await this.loadRide(id);
        setTimeout(() => { this.mapReady = true; }, 100);
    },
    methods: {
        async loadRide(id) {
            try {
                if (this.actual) {
                    const res = await this.api.get(`/driver-actual-ride`);
                    this.ride = res.data.ride;
                    if (this.fromCoords) this.center = this.fromCoords;
                    else if (this.toCoords) this.center = this.toCoords;
                } else {
                    const res = await this.api.get(`/rides/${id}`);
                    this.ride = res.data.ride;
                    if (this.fromCoords) this.center = this.fromCoords;
                    else if (this.toCoords) this.center = this.toCoords;
                }
            } catch (e) { alert("Ошибка загрузки поездки"); }
        },
        async cancelRide() {
            if (!confirm("Вы уверены, что хотите отменить поездку?")) return;
            try {
                const res = await this.api.post(`/rides/${this.ride.id}/cancel`);
                if (res.data.success) {
                    alert("Поездка отменена");
                    await this.loadRide(this.ride.id);
                }
            } catch (e) { alert("Ошибка при отмене поездки"); }
        },
        async submitReview() {
            if (!this.review) return alert("Введите отзыв");
            try {
                const res = await this.api.post(`/rides/${this.ride.id}/review`, { review: this.review });
                if (res.data.success) {
                    alert("Отзыв отправлен");
                    this.review = '';
                }
            } catch (e) { alert("Ошибка при отправке отзыва"); }
        },
        goBack() {
            this.$router.back();
        },
        formatDate(date) {
            if (!date) return '—';
            try {
                return new Date(date).toLocaleString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            } catch (e) { return '—'; }
        }
    },
};
</script>
