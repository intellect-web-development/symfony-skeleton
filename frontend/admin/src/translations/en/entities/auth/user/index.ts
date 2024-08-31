export default {
  one: 'User',
  many: 'Users',
  properties: {
    id: 'Id',
    created_at: 'CreatedAt',
    updated_at: 'UpdatedAt',
    email: 'Email',
  },
  relations: {
  },
  enums: {

  },
  events: {
    removed: 'User removed',
    created: 'User created',
    edited: 'User edited',
  },
  actions: {
    create: 'Create User',
    edit: 'Edit User',
    delete: 'Delete User',
    show: 'Show User',
  },
};
