export default {
  label: 'Аутентификация',
  entities: {
    user: (await import('./user')).default,
  },
}
