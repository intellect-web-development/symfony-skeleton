<template>
  <div class="container app-background height-100">
    <div class="item">
      <Card class="app-background vertical-center height-100">
        <template #header>
          <div style="display: flex; align-items: center; justify-content: center">
            <img alt="Futures trader logo" class="logo" src="@/assets/img/favicon.ico" width="258" height="256" />
          </div>
        </template>
        <template #title>
          {{ $t('layouts.public.header') }}
        </template>
        <template #subtitle>
          {{ $t('layouts.public.label') }}
        </template>
        <template #content>
          <Toolbar style="border-radius: 1.5rem; padding: 0.5rem" unstyled>
            <template #start>
            </template>

            <template #center>
              <div class="nav">
                <div class="other">
                  <Button :label="$t('layouts.public.navbar.whois')" text plain style="flex-grow: 1" @click="$router.push('/')" />
                </div>
                <div class="login">
                  <Button :label="$t('layouts.public.navbar.login')" text plain style="flex-grow: 1" @click="$router.push({name: 'Login'})" />
                </div>
              </div>
            </template>

            <template #end>
            </template>
          </Toolbar>
        </template>
        <template #footer>
        </template>
      </Card>
    </div>
    <div class="item">
      <RouterView />
    </div>
  </div>
</template>

<script lang="ts">
import { RouterView } from 'vue-router'

export default {
  components: {
    RouterView,
  },
  computed: {
    // todo: это example moment, работает, проверено.
    // momentNow(): string {
    //   return moment(new Date()).format('MMMM Do YYYY, h:mm:ss')
    // },
  }
}
</script>

<style lang="scss" scoped>
a,
.green {
  color: #2c8b45;
  transition: 0.4s;
}

.logo {
  @media (max-width: 992px) {
    width: 196px;
    height: 196px;
  }
}
.nav {
  .login {
    display: flex;
    flex-direction: column;
  }
  .other {
    display: flex;
    @media (min-width: 768px) {
      flex-direction: row;
    }
    flex-direction: column;
  }
  text-align: center;
}
.container {
  .item {
    @media (min-width: 992px) {
      width: 50%;
    }
    flex-direction: column;
    flex-grow: 1;
  }
  @media (min-width: 992px) {
    flex-direction: row;
  }
  flex-direction: column;
  display: flex;
  max-width: 1280px;
  margin: 0 auto;
  padding: 2rem;
  font-weight: normal;
  height: 100%;
}
.p-card {
  box-shadow: none;
}
</style>
