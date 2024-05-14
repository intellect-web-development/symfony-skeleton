import {createRouter, createWebHistory} from 'vue-router'
import type { RouteLocationNormalized, NavigationGuardNext } from "vue-router";
import publicAuthMiddleware from "@/middleware/publicAuthMiddleware";
import panelAuthMiddleware from "@/middleware/panelAuthMiddleware";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: () => import('@/layouts/Public.vue'),
      beforeEnter: (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => publicAuthMiddleware(to, from, next),
      children: [
        {
          path: '',
          name: 'Main',
          component: () => import('@/views/Public/AboutProject.vue')
        },
        {
          path: 'login',
          name: 'Login',
          component: () => import('@/views/Public/Login.vue')
        },
        {
          path: 'restore-access',
          name: 'RestoreAccess',
          component: () => import('@/views/Public/RestoreAccess.vue')
        },
        {
          path: ':catchAll(.*)*',
          component: () => import('@/views/Error/404.vue')
        },
      ]
    },
    {
      path: '/panel/',
      component: () => import('@/layouts/Protected.vue'),
      beforeEnter: (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => panelAuthMiddleware(to, from, next),
      children: [
        {
          path: 'welcome',
          name: 'Welcome',
          component: () => import('@/views/Panel/Welcome.vue')
        },
        {
          path: ':catchAll(.*)*',
          component: () => import('@/views/Error/404.vue')
        },
      ],
    },
    {
      path: '/:catchAll(.*)*',
      component: () => import('@/views/Error/404.vue')
    },
  ]
})

export default router
