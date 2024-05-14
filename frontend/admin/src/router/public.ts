export default [
    {
        path: '',
        name: 'Main',
        component: () => import('@/views/Public/AboutProject.vue')
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
