export default [
    {
        path: 'welcome',
        name: 'Welcome',
        component: () => import('@/views/Protected/Welcome.vue')
    },
    {
        path: ':catchAll(.*)*',
        component: () => import('@/views/Error/404.vue')
    },
];
