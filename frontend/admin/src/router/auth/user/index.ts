const routes = [
  {
    path: 'auth/users',
    name: 'Auth_User_Search',
    component: () => import('@/pages/Protected/auth/user/Auth_User_SearchPage.vue'),
  },
  {
    path: 'auth/users/create',
    name: 'Auth_User_Create',
    component: () => import('@/pages/Protected/auth/user/Auth_User_CreatePage.vue'),
  },
  {
    path: 'auth/users/details/:id',
    name: 'Auth_User_Details',
    component: () => import('@/pages/Protected/auth/user/Auth_User_DetailsPage.vue'),
  },
];

export default routes;
