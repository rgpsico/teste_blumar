import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.role === 'admin',
    isOwner: (state) => state.user?.role === 'owner',
    isTenant: (state) => state.user?.role === 'tenant',
  },

  actions: {
    async login(credentials) {
      try {
        const response = await axios.post('/api/login', credentials);
        this.token = response.data.access_token;
        this.user = response.data.user;
        localStorage.setItem('token', this.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        return response.data;
      } catch (error) {
        throw error;
      }
    },

    async register(userData) {
      try {
        const response = await axios.post('/api/register', userData);
        this.token = response.data.access_token;
        this.user = response.data.user;
        localStorage.setItem('token', this.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        return response.data;
      } catch (error) {
        throw error;
      }
    },

    async logout() {
      try {
        await axios.post('/api/logout');
      } catch (error) {
        console.error('Erro ao fazer logout:', error);
      } finally {
        this.user = null;
        this.token = null;
        localStorage.removeItem('token');
        delete axios.defaults.headers.common['Authorization'];
      }
    },

    async checkAuth() {
      if (this.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        try {
          const response = await axios.get('/api/me');
          this.user = response.data;
        } catch (error) {
          this.logout();
        }
      }
    },

    async updateProfile(data) {
      try {
        const response = await axios.put('/api/profile', data);
        this.user = response.data;
        return response.data;
      } catch (error) {
        throw error;
      }
    },
  },
});
