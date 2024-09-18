import router from "@/router";

const items: any = [
  {
    label: 'entities.auth.label',
    items: [
      {
        label: 'entities.auth.entities.user.many',
        icon: 'pi pi-list',
        command: () => {
          router.push({ name: 'Auth_User_Search' });
        },
      },
    ],
  },
];

export default items;
