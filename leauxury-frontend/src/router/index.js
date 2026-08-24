import { createRouter, createWebHistory } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const routes = [
  {
    path: '/',
    component: () => import('../layouts/PublicLayout.vue'),
    children: [
      { path: '', name: 'home', component: () => import('../views/Home.vue') },
      { path: 'products', name: 'products', component: () => import('../views/Products.vue') },
      { path: 'products/:id', name: 'product-detail', component: () => import('../views/ProductDetail.vue') },
      { path: 'gallery', name: 'gallery', component: () => import('../views/Gallery.vue') },
      { path: 'articles', name: 'articles', component: () => import('../views/Articles.vue') },
      { path: 'artikel/:slug', name: 'article-detail', component: () => import('../views/ArticleDetail.vue') },
      { path: 'contact', name: 'contact', component: () => import('../views/Contact.vue') },
    ],
  },
  {
    path: '/admin',
    component: () => import('../layouts/AdminLayout.vue'),
    children: [
      { path: 'hixvi', name: 'admin-login', component: () => import('../views/admin/Login.vue') },
      {
        path: 'dashboard',
        name: 'admin-dashboard',
        component: () => import('../views/admin/Dashboard.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'products',
        name: 'admin-products',
        component: () => import('../views/admin/Products.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'products/create',
        name: 'admin-product-create',
        component: () => import('../views/admin/ProductForm.vue'),
        meta: { requiresAuth: true },
        },
        {
        path: 'products/:id/edit',
        name: 'admin-product-edit',
        component: () => import('../views/admin/ProductForm.vue'),
        meta: { requiresAuth: true },
        },
        {
        path: 'articles',
        name: 'admin-articles',
        component: () => import('../views/admin/Articles.vue'),
        meta: { requiresAuth: true },
        },
        {
        path: 'articles/create',
        name: 'admin-article-create',
        component: () => import('../views/admin/ArticleForm.vue'),
        meta: { requiresAuth: true },
        },
        {
        path: 'articles/:id/edit',
        name: 'admin-article-edit',
        component: () => import('../views/admin/ArticleForm.vue'),
        meta: { requiresAuth: true },
        },
        {
          path: 'featured',
          name: 'admin-featured',
          component: () => import('../views/admin/FeaturedProducts.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'categories',
          name: 'admin-categories',
          component: () => import('../views/admin/Categories.vue'),
          meta: { requiresAuth: true },
        },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const { isAuthenticated } = useAuth()

  if (to.meta.requiresAuth && !isAuthenticated()) {
    next({ name: 'admin-login' })
  } else {
    next()
  }
})

export default router