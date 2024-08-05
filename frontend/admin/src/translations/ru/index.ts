export default {
    main: {
        project_name: 'Vue skeleton',
        'api-contract-is-break': '',
        'error': 'Ошибка',
        'success': 'Успешно',
        'cruds': {
            'list': 'Список',
            'create': 'Создать',
            'remove': 'Удалить',
        }
    },
    components: {
        'smart-form': {
            'violations-out-of-payload': 'Прочие ошибки',
        },
        'security': {
            'loading': 'Загрузка...',
            'check': 'Проверка безопасности',
            'no-access': 'Доступ запрещен',
        }
    },
    layouts: {
        public: (await import('@/layouts/Public/translations/ru')).default,
        protected: (await import('@/layouts/Protected/translations/ru')).default,

        // 'public': {
        //     'header': 'Проект "Skeleton"',
        //     'label': 'Страница входа в SPA-админку',
        //     'navbar': {
        //         'whois': 'О проекте',
        //         'login': 'Войти',
        //     },
        // },
        // 'panel': {
        //     'nav': {
        //         'profile': 'Профиль',
        //         'logout': 'Выход из системы',
        //     },
        //     'actions': {
        //         'success-logout': 'Выход из системы произведен успешно',
        //     },
        // },
    },
    views: {
        common: {
            error: {
                404: (await import('@/views/Common/Error/404/translations/ru')).default,
            },
        },
        protected: {
            welcome: (await import('@/views/Protected/Welcome/translations/ru')).default,
        },
        public: {
            welcome: (await import('@/views/Public/Welcome/translations/ru')).default,
            login: (await import('@/views/Public/Login/translations/ru')).default,
        },
    }
};
