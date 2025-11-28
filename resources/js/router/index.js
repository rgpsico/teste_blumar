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
    path: '/admin/inquilinos',
    name: 'AdminTenantList',
    component: () => import('../views/Admin/Tenants.vue'),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/inquilinos/criar',
    name: 'AdminTenantCreate',
    component: () => import('../views/Admin/Tenants.vue'),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/inquilinos/:id/editar',
    name: 'AdminTenantEdit',
    component: () => import('../views/Admin/Tenants.vue'),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/proprietarios',
    name: 'AdminOwnerList',
    component: () => import('../views/Admin/Owners.vue'),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/proprietarios/criar',
    name: 'AdminOwnerCreate',
    component: () => import('../views/Admin/Owners.vue'),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/proprietarios/:id/editar',
    name: 'AdminOwnerEdit',
    component: () => import('../views/Admin/Owners.vue'),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/comunidades',
    name: 'AdminCommunityList',
    component: () => import('../views/Admin/Communities.vue'),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/logs',
    name: 'AdminLogsDashboard',
    component: () => import('../views/Admin/LogsDashboard.vue'),
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

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // Se requer autenticação, verifica se está autenticado
  if (to.meta.requiresAuth) {
    // Se não está autenticado, redireciona para login
    if (!authStore.isAuthenticated) {
      next({ name: 'Login' });
      return;
    }

    // Se está autenticado mas não tem user, aguarda checkAuth
    if (!authStore.user) {
      await authStore.checkAuth();
    }

    // Após checkAuth, verifica se ainda está autenticado
    if (!authStore.isAuthenticated) {
      next({ name: 'Login' });
      return;
    }

    // Verifica se o usuário tem a role necessária
    if (to.meta.role && authStore.user?.role !== to.meta.role) {
      next({ name: 'Home' });
      return;
    }
  }

  next();
});

export default router;
