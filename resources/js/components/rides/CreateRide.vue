<template>
    <div class="bg-white rounded-lg p-5 mb-6 shadow-sm border border-gray-200">
        <div class="mb-5">
            <h3 class="text-xl font-bold text-gray-800">Создать новую поездку</h3>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Карта слева -->
            <div class="lg:col-span-2">
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <l-map
                        v-model:zoom="zoom"
                        :center="center"
                        @click="onMapClick"
                        :useGlobalLeaflet="true"
                        style="height: 600px; width: 100%;"
                    >
                        <l-tile-layer
                            url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                        ></l-tile-layer>

                        <l-marker
                            v-if="points[0]"
                            :lat-lng="[points[0].lat, points[0].lng]"
                            :icon="startIcon"
                        >
                            <l-popup>Точка посадки</l-popup>
                        </l-marker>

                        <l-marker
                            v-if="points[1]"
                            :lat-lng="[points[1].lat, points[1].lng]"
                            :icon="endIcon"
                        >
                            <l-popup>Точка высадки</l-popup>
                        </l-marker>

                        <l-polyline
                            v-if="routePath.length > 0"
                            :lat-lngs="routePath"
                            :color="'#3b82f6'"
                            :weight="5"
                            :opacity="0.7"
                        ></l-polyline>
                    </l-map>
                </div>
            </div>

            <!-- Панель справа -->
            <div class="flex flex-col gap-4">
                <!-- Поиск адресов -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">
                        <i class="fas fa-search"></i> Поиск адресов
                    </h4>

                    <div class="space-y-3">
                        <div class="relative">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                <i class="fas fa-map-marker-alt text-green-500"></i> Точка посадки
                            </label>
                            <input
                                v-model="searchFrom"
                                @input="searchAddress('from')"
                                @focus="showFromResults = true"
                                type="text"
                                placeholder="Введите адрес..."
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            />
                            <div v-if="showFromResults && fromResults.length > 0" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                <div
                                    v-for="(result, idx) in fromResults"
                                    :key="idx"
                                    @click="selectAddress('from', result)"
                                    class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 text-xs"
                                >
                                    <div class="font-medium text-gray-900">{{ result.display_name }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                <i class="fas fa-map-marker-alt text-red-500"></i> Точка высадки
                            </label>
                            <input
                                v-model="searchTo"
                                @input="searchAddress('to')"
                                @focus="showToResults = true"
                                type="text"
                                placeholder="Введите адрес..."
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            />
                            <div v-if="showToResults && toResults.length > 0" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                <div
                                    v-for="(result, idx) in toResults"
                                    :key="idx"
                                    @click="selectAddress('to', result)"
                                    class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 text-xs"
                                >
                                    <div class="font-medium text-gray-900">{{ result.display_name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Точки маршрута -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">
                        <i class="fas fa-route"></i> Точки маршрута
                    </h4>

                    <div v-if="points[0]" class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-md mb-2">
                        <div class="flex-1">
                            <strong class="text-xs text-gray-800 block">Посадка</strong>
                            <small class="text-xs text-gray-600">
                                {{ routeInfo?.from_address || (points[0] ? points[0].lat.toFixed(4) + ', ' + points[0].lng.toFixed(4) : '') }}
                            </small>
                        </div>
                        <button @click="removePoint(0)" class="bg-red-500 hover:bg-red-600 text-white rounded w-6 h-6 flex items-center justify-center text-xs ml-2">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div v-if="points[1]" class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-md mb-2">
                        <div class="flex-1">
                            <strong class="text-xs text-gray-800 block">Высадка</strong>
                            <small class="text-xs text-gray-600">
                                {{ routeInfo?.to_address || (points[1] ? points[1].lat.toFixed(4) + ', ' + points[1].lng.toFixed(4) : '') }}
                            </small>
                        </div>
                        <button @click="removePoint(1)" class="bg-red-500 hover:bg-red-600 text-white rounded w-6 h-6 flex items-center justify-center text-xs ml-2">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div v-if="!points[0] && !points[1]" class="text-center py-4 text-gray-500 text-xs">
                        <i class="fas fa-info-circle mb-2 block text-lg"></i>
                        Кликните на карту или используйте поиск
                    </div>

                    <button
                        v-if="points.length > 0"
                        @click="clearPoints"
                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-semibold mt-2"
                    >
                        <i class="fas fa-trash"></i> Очистить
                    </button>
                </div>

                <!-- Информация о маршруте -->
                <div v-if="loading" class="rounded-lg p-4 text-gray-800 bg-white shadow-sm flex items-center justify-center">
                    <h1 class="text-sm"><i class="fa-solid fa-spinner fa-spin-pulse"></i> Загрузка...</h1>
                </div>

                <div v-if="routeInfo && !loading" class="rounded-lg p-4 text-gray-800 bg-white shadow-sm border border-gray-200">
                    <h4 class="text-sm font-semibold mb-3">
                        <i class="fa-solid fa-chart-line"></i> Информация о поездке
                    </h4>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <span class="text-xs text-gray-600">
                                <i class="fas fa-route"></i> Расстояние
                            </span>
                            <strong class="text-sm">{{ routeInfo.distance_km }} км</strong>
                        </div>

                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <span class="text-xs text-gray-600">
                                <i class="fas fa-clock"></i> Время
                            </span>
                            <strong class="text-sm">{{ routeInfo.duration_min }} мин</strong>
                        </div>

                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <span class="text-xs text-gray-600">
                                <i class="fas fa-coins"></i> Стоимость
                            </span>
                            <strong class="text-sm">{{ routeInfo.estimated_cost }} TJS</strong>
                        </div>
                    </div>

                    <button @click="createRide" class="w-full bg-blue-500 text-white hover:bg-blue-600 px-4 py-3 rounded-md text-sm font-bold transition-all">
                        <i class="fas fa-plus-circle"></i> Создать поездку
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import "leaflet/dist/leaflet.css";
import { LMap, LTileLayer, LMarker, LPolyline, LPopup } from "@vue-leaflet/vue-leaflet";
import L from "leaflet";

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
    iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
});

export default {
    components: { LMap, LTileLayer, LMarker, LPolyline, LPopup },
    data() {
        return {
            zoom: 14,
            center: [40.271683, 69.642606],
            points: [],
            routePath: [],
            routeInfo: null,
            loading: false,
            searchFrom: '',
            searchTo: '',
            fromResults: [],
            toResults: [],
            showFromResults: false,
            showToResults: false,
            searchTimeout: null,
            startIcon: new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            }),
            endIcon: new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        };
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside);
    },
    methods: {
        handleClickOutside(e) {
            if (!e.target.closest('.relative')) {
                this.showFromResults = false;
                this.showToResults = false;
            }
        },
        async searchAddress(type) {
            const query = type === 'from' ? this.searchFrom : this.searchTo;

            if (query.length < 3) {
                if (type === 'from') this.fromResults = [];
                else this.toResults = [];
                return;
            }

            clearTimeout(this.searchTimeout);

            this.searchTimeout = setTimeout(async () => {
                try {
                    // Nominatim с увеличенным лимитом и поддержкой разных языков
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=10&countrycodes=tj&accept-language=ru,tg,en`
                    );
                    const data = await response.json();

                    // Дополнительный поиск через Photon API (поддерживает кириллицу и таджикский лучше)
                    const photonResponse = await fetch(
                        `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=10&lat=38.5598&lon=68.7738`
                    );
                    const photonData = await photonResponse.json();

                    // Объединяем результаты и убираем дубликаты
                    const combined = [
                        ...data,
                        ...photonData.features.map(f => ({
                            lat: f.geometry.coordinates[1],
                            lon: f.geometry.coordinates[0],
                            display_name: f.properties.name
                                ? `${f.properties.name}, ${f.properties.city || f.properties.state || ''}`
                                : f.properties.street || 'Безымянная точка'
                        }))
                    ].filter(r => r.display_name && r.lat && r.lon);

                    // Убираем дубликаты по координатам
                    const unique = combined.filter((item, index, self) =>
                            index === self.findIndex(t =>
                                Math.abs(t.lat - item.lat) < 0.001 && Math.abs(t.lon - item.lon) < 0.001
                            )
                    ).slice(0, 15); // Показываем максимум 15 результатов

                    if (type === 'from') {
                        this.fromResults = unique;
                        this.showFromResults = true;
                    } else {
                        this.toResults = unique;
                        this.showToResults = true;
                    }
                } catch (error) {
                    console.error('Ошибка поиска:', error);

                    // Если API не работают, пробуем только Nominatim
                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=10&countrycodes=tj&accept-language=ru,tg,en`
                        );
                        const data = await response.json();

                        if (type === 'from') {
                            this.fromResults = data;
                            this.showFromResults = true;
                        } else {
                            this.toResults = data;
                            this.showToResults = true;
                        }
                    } catch (e) {
                        console.error('Все API недоступны:', e);
                    }
                }
            }, 500);
        },
        selectAddress(type, result) {
            const lat = parseFloat(result.lat);
            const lng = parseFloat(result.lon);

            if (type === 'from') {
                this.searchFrom = result.display_name;
                this.showFromResults = false;

                if (this.points[0]) {
                    this.points[0] = { lat, lng };
                } else {
                    this.points.push({ lat, lng });
                }
            } else {
                this.searchTo = result.display_name;
                this.showToResults = false;

                if (this.points[1]) {
                    this.points[1] = { lat, lng };
                } else {
                    if (this.points.length === 0) {
                        this.points.push(null);
                    }
                    this.points[1] = { lat, lng };
                }
            }

            this.center = [lat, lng];

            if (this.points[0] && this.points[1]) {
                this.calculateRoute();
            }
        },
        onMapClick(e) {
            if (this.points.length >= 2) {
                alert('Максимум 2 точки! Очистите текущие точки для добавления новых.');
                return;
            }

            this.points.push({
                lat: e.latlng.lat,
                lng: e.latlng.lng
            });

            if (this.points.length === 2) {
                this.calculateRoute();
            }
        },
        removePoint(index) {
            this.points.splice(index, 1);
            this.routePath = [];
            this.routeInfo = null;

            if (index === 0) {
                this.searchFrom = '';
            } else {
                this.searchTo = '';
            }
        },
        clearPoints() {
            this.points = [];
            this.routePath = [];
            this.routeInfo = null;
            this.searchFrom = '';
            this.searchTo = '';
        },
        async calculateRoute() {
            if (this.points.length < 2) {
                alert('Добавьте обе точки на карту!');
                return;
            }

            this.loading = true;
            const start = [this.points[0].lng, this.points[0].lat];
            const end = [this.points[1].lng, this.points[1].lat];

            this.api.post('/rides/calculate-ride', { start: start, end: end })
                .then((res) => {
                    if(res.data.success) {
                        this.routeInfo = res.data.ride;
                        this.routePath = res.data.ride.geometry;
                        this.loading = false;
                    } else {
                        alert("Ошибка при построении маршрута!");
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        createRide() {
            if (!this.routeInfo) {
                alert('Сначала постройте маршрут!');
                return;
            }

            const rideData = {
                from: [this.points[0].lat, this.points[0].lng],
                to: [this.points[1].lat, this.points[1].lng],
                from_address: this.routeInfo.from_address,
                to_address: this.routeInfo.to_address,
                distance: this.routeInfo.distance_km,
                duration: this.routeInfo.duration_min,
                price: this.routeInfo.estimated_cost,
                geometry: this.routePath,
                passenger_id: this.userId
            };

            this.api.post('/rides', rideData).then((res) => {
                if (res.data.success) {
                    this.clearPoints();
                    this.$router.push('/rides/' + res.data.ride.id);
                }
            });

            this.$emit('ride-created');
            this.clearPoints();
        }
    },
    computed: {
        userId() {
            return localStorage.getItem('userId');
        }
    }
};
</script>
