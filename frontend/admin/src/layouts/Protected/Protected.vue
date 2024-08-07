<template>
  <div class="layoutContainer">
    <Toolbar>
      <template #start>
        <Logo :to="{name: 'Welcome'}" />
      </template>

      <template #center>
      </template>

      <template #end>
        <div class="userBar">
          <Button class="userBar__button" type="button" @click="userBarToggle" text aria-haspopup="true" aria-controls="userBar_tiered_menu">
            <span class="userEmail">{{ userEmail }}</span>
            <Avatar class="userAvatar" icon="pi pi-user" />
          </Button>
          <TieredMenu class="userBar__tieredMenu" ref="menu" id="userBar_tiered_menu" :model="profileItems" popup />
        </div>
      </template>
    </Toolbar>
    <div class="workSpace">
      <RouterView />
    </div>
  </div>
  <Toast position="bottom-right" />
</template>

<script lang="ts">
import {forgetToken} from "@/service/token/tokenService";
import {useAuthStore} from "@/stores/auth/authTokenStore";
import Logo from "@/components/Common/Logo/Logo.vue";

const authStore = useAuthStore();

let tPath = 'layouts.protected.';

export default {
  components: {Logo},
  computed: {
    userEmail(): string
    {
      return authStore.userEmail;
    }
  },
  data() {
    return {
      tPath: tPath,
      profileItems: [
        {
          label: this.$t(tPath + 'nav.profile'),
          icon: 'pi pi-user',
          command: () => {
            // todo: реализовать переход в профиль
            this.$toast.add({ severity: 'info', summary: this.$t('main.success'), detail: 'Кнопка еще не реализована. Когда мы ее сделаем - она перенаправит Вас на Ваш профиль.', life: 5000 });
          }
        },
        {
          label: this.$t(tPath + 'nav.logout'),
          icon: 'pi pi-sign-out',
          command: () => {
            authStore.invalidateRefreshToken();
            this.$router.push({ name: 'Login' })
            this.$nextTick(
                () => {
                  this.$toast.add({ severity: 'success', summary: this.$t('main.success'), detail: this.$t('layouts.panel.actions.success-logout'), life: 3000 });
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
.layoutContainer {
  margin: 0 auto;
  font-weight: normal;
  height: 100%;
  display: flex;
  flex-direction: column;

  .workSpace {
    height: 100%;
    display: flex;
    flex-direction: column;
  }
}
.userBar {
  &__button {
    .userEmail {
      margin-right: 0.5rem;
    }
    .userAvatar {
      background-color: #45b281;
      color: #1a2551;
    }
  }
}
</style>
