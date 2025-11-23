import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/Public/PropertyList.vue'),
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Auth/Login.vue'),
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/Auth/Register.vue'),
  },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: () => import('../views/Admin/Dashboard.vue'),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/owner',
    name: 'OwnerDashboard',
    component: () => import('../views/Owner/Dashboard.vue'),
    meta: { requiresAuth: true, role: 'owner' },
  },
  {
    path: '/tenant',
    name: 'TenantDashboard',
    component: () => import('../views/Tenant/Dashboard.vue'),
    meta: { requiresAuth: true, role: 'tenant' },
  },
  {
    path: '/property/:id',
    name: 'PropertyDetail',
    component: () => import('../views/Public/PropertyDetail.vue'),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'Login' });
  } else if (to.meta.role && authStore.user?.role !== to.meta.role) {
    next({ name: 'Home' });
  } else {
    next();
  }
});

export default router;
