import {createRouter, createWebHistory} from 'vue-router'
import type { RouteLocationNormalized, NavigationGuardNext } from "vue-router";
import publicAuthMiddleware from "@/middleware/publicAuthMiddleware";
import protectedAuthMiddleware from "@/middleware/protectedAuthMiddleware";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: () => import('@/layouts/Public/Public.vue'),
      beforeEnter: (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => publicAuthMiddleware(to, from, next),
      children: (await import('@/router/public')).default
    },
    {
      path: '/admin/',
      component: () => import('@/layouts/Protected/Protected.vue'),
      beforeEnter: (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => protectedAuthMiddleware(to, from, next),
      children: (await import('@/router/admin')).default
    },
    {
      path: '/:catchAll(.*)*',
      component: () => import('@/views/Error/404/404.vue')
    },
  ]
})

export default router
