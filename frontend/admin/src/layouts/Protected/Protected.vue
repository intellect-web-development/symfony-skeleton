<template>
  <div class="container-full">
    <Toolbar class="toolbar">
      <template #start>
        <Button icon="pi pi-bars" text plain @click="visibleDrawer = true" />
        <Logo :to="{name: 'ProtectedWelcome'}" />
      </template>

      <template #center>
      </template>

      <template #end>
        <div class="userBar">
          <Button class="userBar__button" type="button" @click="userBarToggle" text aria-haspopup="true" aria-controls="userBar_tiered_menu">
            <span class="userEmail">{{ userEmail }}</span>
            <Avatar class="userBar__button__avatar" icon="pi pi-user" shape="circle" />
          </Button>
          <TieredMenu class="userBar__tieredMenu" ref="menu" id="userBar_tiered_menu" :model="profileItems" popup />
        </div>
      </template>
    </Toolbar>
    <div class="workSpace">
      <div class="workSpace__workZone">
        <RouterView />
      </div>
      <ProtectedSidebar
          :visible="visibleDrawer"
          @visibleChanged="visibleDrawer = $event"
      />
    </div>
  </div>
  <Toast position="bottom-right" />
  <ConfirmDialog />
</template>

<script lang="ts">
import {forgetToken} from "@/service/token/tokenService";
import {useAuthStore} from "@/stores/auth/authTokenStore";
import Logo from "@/components/Common/Logo/Logo.vue";
import ProtectedSidebar from "@/components/ProtectedSidebar/ProtectedSidebar.vue";

const authStore = useAuthStore();

let tPath = 'layouts.protected.';

export default {
  components: {ProtectedSidebar, Logo},
  computed: {
    userEmail(): string
    {
      return authStore.userEmail;
    }
  },
  data() {
    return {
      tPathMain: 'main.',
      visibleDrawer: false,
      tPath: tPath,
      profileItems: [
        {
          label: this.$t(tPath + 'nav.logout'),
          icon: 'pi pi-sign-out',
          command: () => {
            authStore.invalidateRefreshToken();
            this.$router.push({ name: 'Login' })
            this.$nextTick(
                () => {
                  this.$toast.add({
                    severity: 'success',
                    summary: this.$t('main.success'),
                    detail: this.$t('layouts.protected.actions.success-logout'),
                    life: 3000
                  });
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
@import "@/assets/smart-grid";

.toolbar {
  @include row-flex();
}

.workSpace {
  height: 100%;
  @include row-flex();

  &__workZone {
    @include col();
    @include size(12);
  }
}
.userBar {
  &__button {
    .userEmail {
      margin-right: 0.5rem;
    }

    color: var(--app-main-color);
  }
}
</style>
