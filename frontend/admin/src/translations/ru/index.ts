export default {
    main: {
        project_name: 'Vue skeleton',
        error: 'Ошибка',
        success: 'Успешно',
        // 'cruds': {
        //     'list': 'Список',
        //     'create': 'Создать',
        //     'remove': 'Удалить',
        // }
    },
    components: {
        form: {
            smart_form: (await import('@/components/Form/SmartForm/translations/ru')).default,
        },
        security: {
            security_loader: (await import('@/components/Security/SecurityLoader/translations/ru')).default,
            page_loader: (await import('@/components/Security/PageLoader/translations/ru')).default,
            security_guard: (await import('@/components/Security/SecurityGuard/translations/ru')).default,
        },
    },
    layouts: {
        public: (await import('@/layouts/Public/translations/ru')).default,
        protected: (await import('@/layouts/Protected/translations/ru')).default,
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
