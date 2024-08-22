export default [
    {
        path: 'welcome',
        name: 'ProtectedWelcome',
        component: () => import('@/pages/Protected/Welcome/Welcome.vue'),
    },
    {
        path: ':catchAll(.*)*',
        component: () => import('@/pages/Error/404/404.vue'),
    },
];
