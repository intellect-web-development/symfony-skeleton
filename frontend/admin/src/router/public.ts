export default [
    {
        path: '',
        name: 'PublicWelcome',
        component: () => import('@/views/Public/Welcome/Welcome.vue')
    },
    {
        path: 'login',
        name: 'Login',
        component: () => import('@/views/Public/Login/Login.vue')
    },
    {
        path: ':catchAll(.*)*',
        component: () => import('@/views/Error/404.vue')
    },
];
