import router from "@/router";

// For search icons: https://primevue.org/icons

const tPathNav = 'nav.';

const items = [
    {
        label: tPathNav + 'profile.label',
        icon: 'pi pi-users',
        items: [
            {
                label: tPathNav + 'profile.entities.client.many',
                icon: 'pi pi-user',
                command: () => {
                    router.push({ name: 'Profile_Client_Search' });
                },
            },
        ],
    },
];

export default items;