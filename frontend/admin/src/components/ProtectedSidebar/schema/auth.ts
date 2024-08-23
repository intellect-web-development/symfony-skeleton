import router from "@/router";

const tPathNav = 'nav.';

const items: any = [
  {
    label: tPathNav + 'auth.label',
    icon: 'pi pi-list',
    items: [
      {
        label: tPathNav + 'auth.entities.user.many',
        icon: 'pi pi-list',
        command: () => {
          router.push({ name: 'Auth_User_Search' });
        },
      },
    ],
  },
];

export default items;
