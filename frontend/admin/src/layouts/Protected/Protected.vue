<template>
  <div class="container-public">
    <Toolbar class="menubar">
      <template #start>
        <RouterLink :to="{name: 'Welcome'}" class="logo-link">
          <div class="logo">
            <img alt="Skeleton logo" src="../../assets/img/favicon.ico" width="50" height="50" />
            <span class="project-name"> {{ $t('main.project-name') }}</span>
          </div>
        </RouterLink>
      </template>

      <template #center>
      </template>

      <template #end>
        <Button type="button" @click="userBarToggle" text aria-haspopup="true" aria-controls="overlay_tmenu">
          <span class="mr-3">{{ userEmail }}</span> <Avatar icon="pi pi-user" style="background-color: #45b281; color: #1a2551" />
        </Button>
        <TieredMenu ref="menu" id="overlay_tmenu" :model="profileItems" popup />
      </template>
    </Toolbar>
    <div class="main">
      <RouterView />
    </div>
  </div>
  <Toast position="bottom-right" group="br" />
</template>

<script lang="ts">
import {forgetToken} from "@/service/token/tokenService";
import {useAuthStore} from "@/stores/auth/authToken";

const authStore = useAuthStore();

export default {
  computed: {
    userEmail(): string
    {
      return authStore.userEmail;
    }
  },
  data() {
    return {
      tPath: 'layouts.protected.',
      profileItems: [
        {
          label: this.$t('layouts.panel.nav.profile'),
          icon: 'pi pi-user',
          command: () => {
            // todo: реализовать переход в профиль
            this.$toast.add({ severity: 'info', summary: this.$t('main.success'), detail: 'Кнопка еще не реализована. Когда мы ее сделаем - она перенаправит Вас на Ваш профиль.', group: 'br', life: 5000 });
          }
        },
        {
          label: this.$t('layouts.panel.nav.logout'),
          icon: 'pi pi-sign-out',
          command: () => {
            authStore.invalidateRefreshToken();
            this.$router.push({ name: 'Login' })
            this.$nextTick(
                () => {
                  this.$toast.add({ severity: 'success', summary: this.$t('main.success'), detail: this.$t('layouts.panel.actions.success-logout'), group: 'br',life: 3000 });
                }
            )
            forgetToken();
          }
        },
    ]
    };
  },
  methods: {
    userBarToggle(event: MouseEvent) {
      // @ts-ignore
      this.$refs.menu.toggle(event);
    }
  }
};
</script>

<style lang="scss" scoped>
.logo-link {
  text-decoration: none;
  font-size: 1rem;
  font-weight: bold;
}
.container-public {
  margin: 0 auto;
  font-weight: normal;
  height: 100%;
  display: flex;
  flex-direction: column;

  .main {
    height: 100%;
    display: flex;
    flex-direction: column;
  }
}
.logo {
  .project-name {
    margin-left: 0.5rem;
  }
  margin: 0 0.5rem;
  display: flex;
  align-items: center;
}
.p-button.p-button-text {
  height: 100%;
}
</style>
