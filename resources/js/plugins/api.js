import axios from 'axios'

const BASE_URL = (import.meta.env.VITE_API_URL || '') + (import.meta.env.VITE_API_PREFIX || '')
console.log(BASE_URL)
const instance = axios.create({
    baseURL: BASE_URL,
    timeout: 15000,
    headers: {
        'Accept': 'application/json',
    },
})

instance.interceptors.request.use(config => {
    const token = localStorage.getItem('auth_token')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
}, error => {
    return Promise.reject(error)
})

export function get(url, params = {}, config = {}) {
    return instance.get(url, { params, ...config }).then(res => res.data)
}

export function post(url, data = {}, config = {}) {
    return instance.post(url, data, config).then(res => res.data)
}

export function put(url, data = {}, config = {}) {
    return instance.put(url, data, config).then(res => res.data)
}

export function del(url, params = {}, config = {}) {
    return instance.delete(url, { params, ...config }).then(res => res.data)
}

export default instance
