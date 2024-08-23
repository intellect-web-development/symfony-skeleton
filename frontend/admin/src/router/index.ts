import type {NavigationGuardNext, RouteLocationNormalized} from "vue-router";
import {createRouter, createWebHistory} from 'vue-router'
import publicAuthMiddleware from "@/middleware/publicAuthMiddleware";
import protectedAuthMiddleware from "@/middleware/protectedAuthMiddleware";
import {projectName} from "@/router/routerDictionary";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: () => import('@/layouts/Public/Public.vue'),
      beforeEnter: (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => publicAuthMiddleware(to, from, next),
      children: [
        {
          path: '',
          name: 'PublicWelcome',
          component: () => import('@/pages/Public/welcome/Welcome.vue'),
        },
        {
          path: 'login',
          name: 'Login',
          component: () => import('@/pages/Public/login/Login.vue'),
        },
      ],
    },
    {
      path: '/public/',
      component: () => import('@/layouts/Public/Public.vue'),
      beforeEnter: (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => publicAuthMiddleware(to, from, next),
      children: (await import('@/router/public')).default,
    },
    {
      path: '/',
      component: () => import('@/layouts/Protected/Protected.vue'),
      beforeEnter: (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => protectedAuthMiddleware(to, from, next),
      children: (await import('@/router/protected')).default,
    },
    {
      path: '/:catchAll(.*)*',
      component: () => import('@/pages/Common/error/404/404.vue'),
    },
  ]
})

router.beforeEach((to, from, next) => {
  document.title = (to.meta.title as string | undefined) || projectName;
  next();
});

export default router
