import type {NavigationGuardNext, RouteLocationNormalized} from "vue-router";
import {createRouter, createWebHistory} from 'vue-router'
import publicAuthMiddleware from "@/middleware/publicAuthMiddleware";
import protectedAuthMiddleware from "@/middleware/protectedAuthMiddleware";
import {projectName} from "@/router/meta/routerDictionary";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: () => import('@/layouts/Public/Public.vue'),
      beforeEnter: (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => publicAuthMiddleware(to, from, next),
      children: (await import('@/router/public')).default,
    },
    {
      path: '/admin/',
      component: () => import('@/layouts/Protected/Protected.vue'),
      beforeEnter: (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => protectedAuthMiddleware(to, from, next),
      children: (await import('@/router/admin')).default,
    },
    {
      path: '/:catchAll(.*)*',
      component: () => import('@/views/Common/Error/404/404.vue'),
    },
  ]
})

router.beforeEach((to, from, next) => {
  document.title = (to.meta.title as string | undefined) || projectName;
  next();
});

export default router
