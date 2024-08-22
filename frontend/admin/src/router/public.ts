export default [
    {
        path: ':catchAll(.*)*',
        component: () => import('@/pages/Error/404/404.vue'),
    },
];
