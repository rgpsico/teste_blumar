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
    path: '/admin/inquilinos/novo',
    name: 'AdminTenantCreate',
    component: () => import('../views/Admin/UserForm.vue'),
    props: {
      role: 'tenant',
      mode: 'create',
      title: 'Novo Inquilino',
      subtitle: 'Cadastrar um novo inquilino',
      returnRoute: '/admin/inquilinos'
    },
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/inquilinos/:id/editar',
    name: 'AdminTenantEdit',
    component: () => import('../views/Admin/UserForm.vue'),
    props: (route) => ({
      role: 'tenant',
      mode: 'edit',
      title: 'Editar Inquilino',
      subtitle: 'Atualize os dados do inquilino',
      returnRoute: '/admin/inquilinos',
      id: route.params.id
    }),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/proprietarios',
    name: 'AdminOwnerList',
    component: () => import('../views/Admin/Owners.vue'),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/proprietarios/novo',
    name: 'AdminOwnerCreate',
    component: () => import('../views/Admin/UserForm.vue'),
    props: {
      role: 'owner',
      mode: 'create',
      title: 'Novo Proprietário',
      subtitle: 'Cadastrar um novo proprietário',
      returnRoute: '/admin/proprietarios'
    },
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/proprietarios/:id/editar',
    name: 'AdminOwnerEdit',
    component: () => import('../views/Admin/UserForm.vue'),
    props: (route) => ({
      role: 'owner',
      mode: 'edit',
      title: 'Editar Proprietário',
      subtitle: 'Atualize os dados do proprietário',
      returnRoute: '/admin/proprietarios',
      id: route.params.id
    }),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/comunidades',
    name: 'AdminCommunityList',
    component: () => import('../views/Admin/Communities.vue'),
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/comunidades/nova',
    name: 'AdminCommunityCreate',
    component: () => import('../views/Admin/CommunityForm.vue'),
    props: {
      mode: 'create',
      title: 'Nova Comunidade',
      subtitle: 'Cadastrar uma nova comunidade',
      returnRoute: '/admin/comunidades'
    },
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/comunidades/:id/editar',
    name: 'AdminCommunityEdit',
    component: () => import('../views/Admin/CommunityForm.vue'),
    props: (route) => ({
      mode: 'edit',
      title: 'Editar Comunidade',
      subtitle: 'Atualize os dados da comunidade',
      returnRoute: '/admin/comunidades',
      id: route.params.id
    }),
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
