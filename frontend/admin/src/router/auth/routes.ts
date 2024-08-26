const routes = [
  {
    path: 'auth',
    name: 'Auth_Index',
    component: () => import('@/pages/Protected/auth/Index.vue')
  },
  ...(await import('./user')).default,
];

export default routes;
