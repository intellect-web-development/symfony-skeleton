<template>
  <RouterLink :to="to" class="logo-link">
    <div class="logo">
      <img alt="Skeleton logo" src="@/assets/img/favicon.ico" width="50" height="50" />
      <span class="project-name"> {{ $t(tPathMain + 'project_name') }}</span>
    </div>
  </RouterLink>
</template>

<script lang="ts">
export default {
  props: {
    to: {
      type: Object,
      default: {name: 'Welcome'},
    },
  },
  data() {
    return {
      tPath: 'components.common.logo.',
      tPathMain: 'main.',
    }
  },
}
</script>

<style lang="scss" scoped>
.logo {
  .project-name {
    margin-left: 0.5rem;
  }
  margin: 0 0.5rem;
  display: flex;
  align-items: center;
}
.logo-link {
  text-decoration: none;
  font-size: 1rem;
  font-weight: bold;
}
</style>
