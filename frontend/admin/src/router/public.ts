import {projectName} from "@/router/meta/routerDictionary";

export default [
    {
        path: '',
        name: 'PublicWelcome',
        component: () => import('@/views/Public/Welcome/Welcome.vue'),
        meta: { title: 'Добро пожаловать | ' + projectName },
    },
    {
        path: 'login',
        name: 'Login',
        component: () => import('@/views/Public/Login/Login.vue'),
        meta: { title: 'Вход | ' + projectName },
    },
    {
        path: ':catchAll(.*)*',
        component: () => import('@/views/Common/Error/404/404.vue'),
        meta: { title: '404 - страница не найдена | ' + projectName },
    },
];
