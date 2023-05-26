<template>
  <q-layout view="hHh LpR fFf">
    <q-header elevated class="bg-dark" height-hint="98">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          <q-btn
            no-caps
            color="dark"
            :label='$t("project.name")'
            :to="{name: 'Main'}"
          />
        </q-toolbar-title>

        <q-btn color="dark" no-caps :label="userEmail">
          <q-menu
            transition-show="rotate"
            transition-hide="rotate"
            fit
            anchor="bottom left"
            self="top left"
          >
            <q-item clickable>
              <q-item-section side>
                <q-icon name="logout" />
              </q-item-section>
              <q-item-section clickable @click="logout">
                Logout
              </q-item-section>
            </q-item>

          </q-menu>
        </q-btn>

      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      class="bg-grey-2"
    >
      Is sidebar
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue';

export default defineComponent({
  name: 'MainLayout',

  created() {
  },

  data() {
    return {
      userEmail: 'shaman@demo.com',
    }
  },

  computed: {
  },

  methods: {
    logout() {
      console.log('Stub for logout');
    }
  },

  setup () {
    const leftDrawerOpen = ref(false)

    return {
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  }
});
</script>
