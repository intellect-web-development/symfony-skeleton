export default {
  label: 'Auth',
  entities: {
    user: (await import('./user')).default,
  },
}
