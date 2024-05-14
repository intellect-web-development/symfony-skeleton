export default [
    {
        path: 'welcome',
        name: 'Welcome',
        component: () => import('@/views/Panel/Welcome.vue')
    },
    {
        path: ':catchAll(.*)*',
        component: () => import('@/views/Error/404.vue')
    },
];
