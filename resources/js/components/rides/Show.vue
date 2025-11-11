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
                    <p class="text-gray-600">У вас нету активной поездки...</p>
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
                            <div v-if="isAdmin" class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600">ID поездки:</span>
                                <span class="font-mono font-medium text-gray-900">{{ ride.id }}</span>
                            </div>
                            <div v-if="isAdmin" class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600">Пассажир ID:</span>
                                <span class="font-mono font-medium text-gray-900">{{ ride.passenger_id ?? 'Пусто' }}</span>
                            </div>
                            <div v-if="isAdmin" class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600">Водитель ID:</span>
                                <span class="font-mono font-medium text-gray-900">{{ ride.driver_id ?? 'Пусто' }}</span>
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
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Управление поездкой</h2>

                        <div v-if="!isPassenger && canChangeStatus" class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Изменить статус</label>
                            <div class="space-y-2">
                                <button
                                    v-for="status in availableStatuses"
                                    :key="status.value"
                                    @click="newStatus = status.value"
                                    :class="[
                                        'w-full px-4 py-3 rounded-lg font-medium transition-all duration-200 text-left flex items-center justify-between',
                                        newStatus === status.value
                                            ? status.activeClass + ' shadow-md transform scale-[1.02]'
                                            : status.inactiveClass + ' hover:shadow-sm'
                                    ]"
                                >
                                    <span class="flex items-center gap-2">
                                        <span class="text-lg">{{ status.icon }}</span>
                                        <span>{{ status.label }}</span>
                                    </span>
                                    <span v-if="newStatus === status.value" class="text-xl">✓</span>
                                </button>
                            </div>
                            <button
                                @click="updateStatus"
                                :disabled="!newStatus || newStatus === ride.status.name"
                                :class="[
                                    'w-full mt-4 font-semibold py-3 rounded-lg transition',
                                    (!newStatus || newStatus === ride.status.name)
                                        ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                        : 'bg-blue-600 hover:bg-blue-700 text-white'
                                ]"
                            >
                                Сохранить изменения
                            </button>
                        </div>

                        <div v-if="isAdmin && !canChangeStatus" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-600 text-center">
                                <span class="text-lg mb-2 block"><i class="fa-solid fa-lock"></i></span>
                                Изменение статуса доступно только для принятых поездок
                            </p>
                        </div>

                        <button v-if="ride.status.name !== 'cancelled'" @click="cancelRide" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-lg transition">
                            Отменить поездку
                        </button>

                        <button v-if="ride.status.name === 'created' && isDriver" @click="takeRide" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg transition mt-2">
                            Взять
                        </button>

                        <div v-if="isPassenger && (ride.status.name === 'completed' || ride.status.name === 'cancelled') && !ride.rating" class="mt-6 ">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Оставить отзыв</h2>
                            <div class="flex items-center mb-3">
                                <template v-for="star in 5" :key="star">
                                    <button type="button" @click="rating = star" class="text-2xl mr-1">
                                        <span :class="star <= rating ? 'text-yellow-400' : 'text-gray-300'">★</span>
                                    </button>
                                </template>
                                <span class="ml-2 text-gray-600">{{ rating }}/5</span>
                            </div>
                            <textarea v-model="review" placeholder="Ваш отзыв..." class="w-full p-3 border border-gray-200 rounded-lg resize-none"></textarea>
                            <button @click="submitReview" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">Отправить</button>
                        </div>
                        <div v-if="ride.rating" class="mt-6 ">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Отзыв</h2>
                            <div class="flex items-center mb-3">
                                <template v-for="star in 5" :key="star">
                                    <button type="button" class="text-2xl mr-1">
                                        <span :class="star <= ride.rating.stars ? 'text-yellow-400' : 'text-gray-300'">★</span>
                                    </button>
                                </template>
                                <span class="ml-2 text-gray-600">{{ ride.rating.stars }}/5</span>
                            </div>
                            <p v-if="ride.rating.comment">{{ ride.rating.comment }}</p>
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
                                    <p v-if="isAdmin"><i class="fa-solid fa-envelope"></i>{{ ride.passenger?.email || '—' }}</p>
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
    props: {
        actual: {
            type: Boolean,
            default: false
        }
    },
    components: { LMap, LTileLayer, LMarker, LPolyline, LPopup },
    data() {
        return {
            ride: null,
            zoom: 13,
            center: [38.5598, 68.7738],
            mapReady: false,
            newStatus: "",
            user_id: localStorage.getItem('userId'),
            review: '',
            rating: 5,
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
        };
    },
    beforeUnmount() {
        this.eventBus.off('ride-event', this.handleRide)
    },
    computed: {
        userRole() {
            return localStorage.getItem("userRole")
        },
        isAdmin() {
            return this.userRole === 'admin';
        },
        isDriver() {
            return this.userRole === 'driver';
        },
        isPassenger() {
            return this.userRole === 'passenger';
        },
        apiUrl() {
            if (this.actual) {
                if (this.isDriver) {
                    return '/driver-actual-ride';
                } else if(this.isPassenger) {
                    return '/passenger-actual-ride';
                }
            }
            const route = useRoute();
            return `/rides/${route.params.id}`;
        },
        fromCoords() {
            if (!this.ride?.from) return null;
            try {
                const coords = typeof this.ride.from === 'string' ? JSON.parse(this.ride.from) : this.ride.from;
                if (coords[0] && coords[1]) return [coords[0], coords[1]];
            } catch (e) {
                console.error('Ошибка парсинга from координат:', e);
            }
            return null;
        },
        toCoords() {
            if (!this.ride?.to) return null;
            try {
                const coords = typeof this.ride.to === 'string' ? JSON.parse(this.ride.to) : this.ride.to;
                if (coords[0] && coords[1]) return [coords[0], coords[1]];
            } catch (e) {
                console.error('Ошибка парсинга to координат:', e);
            }
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
            } catch (e) {
                console.error('Ошибка парсинга geometry:', e);
            }
            if (this.fromCoords && this.toCoords) return [this.fromCoords, this.toCoords];
            return [];
        },
        canChangeStatus() {
            return this.ride?.status?.name === 'accepted' || this.ride?.status?.name === 'in_progress';
        },
        availableStatuses() {
            if (!this.ride) return [];

            const allStatuses = {
                accepted: {
                    value: 'accepted',
                    label: 'Принята',
                    icon: '✅',
                    activeClass: 'bg-blue-100 text-blue-800 border-2 border-blue-500',
                    inactiveClass: 'bg-gray-50 text-gray-700 border border-gray-200'
                },
                in_progress: {
                    value: 'in_progress',
                    label: 'В пути',
                    icon: '🚗',
                    activeClass: 'bg-yellow-100 text-yellow-800 border-2 border-yellow-500',
                    inactiveClass: 'bg-gray-50 text-gray-700 border border-gray-200'
                },
                completed: {
                    value: 'completed',
                    label: 'Завершена',
                    icon: '🏁',
                    activeClass: 'bg-green-100 text-green-800 border-2 border-green-500',
                    inactiveClass: 'bg-gray-50 text-gray-700 border border-gray-200'
                }
            };

            if (this.ride.status?.name === 'accepted') {
                return [allStatuses.accepted, allStatuses.in_progress];
            } else if (this.ride.status?.name === 'in_progress') {
                return [allStatuses.in_progress, allStatuses.completed];
            }

            return [];
        },
        userId() {
            return localStorage.getItem('userId');
        }
    },
    async mounted() {
        this.eventBus.on('ride-event', this.handleRide)
        await this.loadRide();
        setTimeout(() => { this.mapReady = true; }, 100);
    },
    methods: {
        handleRide(ride) {
            console.log('Ride Event:', ride);
            if (typeof this[ride._] === 'function') {
                this[ride._](ride);
            } else {
                console.warn(`Handler ${ride._} not found`, ride);
            }
        },
        deleteRide(ride) {
            if(this.isDriver) {
                this.$router.push('/actual');
            }
        },
        takeRide() {
            this.api.post('/take-ride/' + this.ride.id)
                .then((res) => {
                    this.ride = res.data.ride;
                });
        },
        async loadRide() {
            try {
                const res = await this.api.get(this.apiUrl);
                this.ride = res.data.ride;

                if (this.fromCoords) {
                    this.center = this.fromCoords;
                } else if (this.toCoords) {
                    this.center = this.toCoords;
                }

                this.newStatus = this.ride?.status?.name || "";
            } catch (e) {
                console.error('Ошибка загрузки поездки:', e);
            }
        },
        async updateStatus() {
            if (!this.newStatus) return alert("Выберите новый статус!");
            try {
                const res = await this.api.post(`/rides/change-status/${this.ride.id}`, { status: this.newStatus });
                if (res.data.success) {
                    await this.loadRide();
                }
            } catch (e) {
                console.error('Ошибка обновления статуса:', e);
            }
        },
        async cancelRide() {
            try {
                const res = await this.api.post(`/rides/change-status/${this.ride.id}`, {status: 'cancelled'});
                if (res.data.success) {
                    await this.loadRide();
                }
            } catch (e) {
                console.error('Ошибка отмены поездки:', e);
            }
        },
        async submitReview() {
            if (!this.review && !this.rating) return alert("Введите отзыв");
            try {
                const res = await this.api.post(`/rides/rate/` + this.ride.id, { comment: this.review, rating: this.rating, user_id: this.user_id });
                if (res.data.success) {
                    this.loadRide();
                }
            } catch (e) {
                console.error('Ошибка при отправке отзыва:', e);
            }
        },
        goBack() {
            this.$router.back();
        },
        formatDate(date) {
            if (!date) return '—';
            try {
                return new Date(date).toLocaleString('ru-RU', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                return '—';
            }
        }
    },
};
</script>
