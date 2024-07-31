export default [
    {
        path: '',
        name: 'public_welcome',
        component: () => import('@/views/Public/Welcome.vue')
    },
    {
        path: 'login',
        name: 'Login',
        component: () => import('@/views/Public/Login.vue')
    },
    {
        path: ':catchAll(.*)*',
        component: () => import('@/views/Error/404.vue')
    },
];
