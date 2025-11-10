export default {
    install(app) {
        app.config.globalProperties.getGeo = async () => {
            if (!navigator.geolocation) return null
            return new Promise((resolve) => {
                navigator.geolocation.getCurrentPosition(
                    pos => resolve({ lat: pos.coords.latitude, lng: pos.coords.longitude }),
                    () => resolve(null),
                    { enableHighAccuracy: true, timeout: 10000 }
                )
            })
        }
    }
}
