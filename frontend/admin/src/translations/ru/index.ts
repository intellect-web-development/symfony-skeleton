export default {
    main: {
        project_name: 'Vue skeleton',
        error: 'Ошибка',
        success: 'Успешно',
        go_to_full_form: 'Перейти к полной форме',
        cancel: 'Отменить',
        save: 'Сохранить',
        create: 'Создать',
        edit: 'Редактировать',
        remove: 'Удалить',
        danger_zone: 'Опасная зона',
        remove_confirm: 'Вы уверены что хотите удалить эту сущность?',
        common_fail_load_page: {
            summary: 'Ошибка загрузки данных',
            details: 'Страница либо не существует, либо вы столкнулись с ошибкой сервера. Обратитесь в техническую поддержку для решения проблемы, если вы считаете, что страница должна существовать',
        },
        events: {
            remove_confirmed: 'Удаление подтверждено',
        },
        details: 'Детали',
    },
    components: {
        common: {
            page_loader: (await import('@/components/Common/PageLoader/translations/ru')).default,
            toast: {
                entity_created: (await import('@/components/Common/Toast/EntityCreated/translations/ru')).default,
            }
        },
        form: {
            input: {
                search_input: (await import('@/components/Form/Input/SearchInput/translations/ru')).default,
            },
            smart_form: (await import('@/components/Form/SmartForm/translations/ru')).default,
        },
        security: {
            security_loader: (await import('@/components/Security/SecurityLoader/translations/ru')).default,
            security_guard: (await import('@/components/Security/SecurityGuard/translations/ru')).default,
        },
        search: {
            search_page: (await import('@/components/Search/SearchPage/translations/ru')).default,
            search_module: (await import('@/components/Search/SearchModule/translations/ru')).default,
            search_modes: (await import('./searchModes')).default,
        },
    },
    layouts: {
        public: (await import('@/layouts/Public/translations/ru')).default,
        protected: (await import('@/layouts/Protected/translations/ru')).default,
    },
    pages: {
        public: {
            welcome: (await import('@/pages/Public/welcome/translations/ru')).default,
            login: (await import('@/pages/Public/login/translations/ru')).default,
        },
        protected: {
            welcome: (await import('@/pages/Protected/welcome/translations/ru')).default,
        },
        error: {
            404: (await import('@/pages/Common/error/404/translations/ru')).default,
        },
    },
    entities: (await import('./entities')).default,
};
