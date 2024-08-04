export default [
    {
        path: 'welcome',
        name: 'Welcome',
        component: () => import('@/views/Protected/Welcome/Welcome.vue')
    },
    {
        path: ':catchAll(.*)*',
        component: () => import('@/views/Common/Error/404/404.vue')
    },
];
