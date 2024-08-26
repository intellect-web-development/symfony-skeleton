export default {
  one: 'Пользователь',
  many: 'Пользователи',
  properties: {
    id: 'ID',
    created_at: 'Дата создания',
    updated_at: 'Дата обновления',
    email: 'Email',
  },
  relations: {
  },
  enums: {
  },
  events: {
    removed: 'Пользователь удален',
    created: 'Пользователь создан',
    edited: 'Пользователь отредактирован',
  },
  actions: {
    create: 'Создание пользователя',
    edit: 'Редактирование пользователя',
    delete: 'Удаление пользователя',
    show: 'Просмотр пользователя',
  },
};