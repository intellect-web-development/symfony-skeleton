export default [
    {
        path: 'welcome',
        name: 'ProtectedWelcome',
        component: () => import('@/pages/Protected/welcome/Welcome.vue'),
    },
    {
        path: ':catchAll(.*)*',
        component: () => import('@/pages/Common/error/404/404.vue'),
    },
];
