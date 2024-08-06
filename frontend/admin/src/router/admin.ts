import {projectName} from "@/router/meta/routerDictionary";

export default [
    {
        path: 'welcome',
        name: 'Welcome',
        component: () => import('@/views/Protected/Welcome/Welcome.vue'),
        meta: { title: 'Добро пожаловать | ' + projectName },
    },
    {
        path: ':catchAll(.*)*',
        component: () => import('@/views/Common/Error/404/404.vue'),
        meta: { title: '404 - страница не найдена | ' + projectName },
    },
];
