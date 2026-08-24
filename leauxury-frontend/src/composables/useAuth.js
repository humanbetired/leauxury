import { ref } from 'vue'
import api from '../api/axios'

const admin = ref(JSON.parse(localStorage.getItem('admin_data') || 'null'))
const token = ref(localStorage.getItem('admin_token'))

export function useAuth() {
  const isAuthenticated = () => !!token.value

  const login = async (email, password) => {
    const res = await api.post('/admin/login', { email, password })
    token.value = res.data.token
    admin.value = res.data.admin
    localStorage.setItem('admin_token', res.data.token)
    localStorage.setItem('admin_data', JSON.stringify(res.data.admin))
  }

  const logout = async () => {
    await api.post('/admin/logout')
    token.value = null
    admin.value = null
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_data')
  }

  return { admin, token, isAuthenticated, login, logout }
}