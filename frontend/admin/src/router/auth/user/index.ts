const routes = [
  {
    path: 'auth/users',
    name: 'Auth_User_Search',
    component: () => import('@/pages/Protected/auth/user/AuthUserSearchPage.vue'),
  },
  {
    path: 'auth/users/create',
    name: 'Auth_User_Create',
    component: () => import('@/pages/Protected/auth/user/AuthUserCreatePage.vue'),
  },
  {
    path: 'auth/users/details/:id',
    name: 'Auth_User_Details',
    component: () => import('@/pages/Protected/auth/user/AuthUserDetailsPage.vue'),
  },
];

export default routes;
