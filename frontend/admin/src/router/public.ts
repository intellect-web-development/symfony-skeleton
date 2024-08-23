export default [
    {
        path: ':catchAll(.*)*',
        component: () => import('@/pages/Common/error/404/404.vue'),
    },
];
