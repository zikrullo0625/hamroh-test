export default {
    install(app) {
        const listeners = {}

        app.config.globalProperties.eventBus = {
            on(event, callback) {
                if (!listeners[event]) listeners[event] = []
                listeners[event].push(callback)
            },
            off(event, callback) {
                if (!listeners[event]) return
                listeners[event] = listeners[event].filter(cb => cb !== callback)
            },
            emit(event, payload) {
                if (!listeners[event]) return
                listeners[event].forEach(cb => {
                    if (typeof cb === 'function') cb(payload)
                })
            }
        }
    }
}
