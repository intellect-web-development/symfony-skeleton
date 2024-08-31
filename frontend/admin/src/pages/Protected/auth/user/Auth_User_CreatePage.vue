<template>
  <div class="container">
    <Breadcrumb :home="breadcrumbs.home" :model="breadcrumbs.items" />
    <Card>
      <template #content>
        <Auth_User_CreateView :fallback-form-payload="fallbackFormPayload" />
      </template>
    </Card>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Auth_User_CreateView from "@/views/Protected/auth/user/Auth_User_CreateView.vue";
import {useHead} from "unhead";
import {generateTitleFromBreadcrumbs} from "@/service/metaService";

export default defineComponent({
  components: {Auth_User_CreateView},

  created() {
    useHead({
      title: generateTitleFromBreadcrumbs(this.breadcrumbs)
    })
  },

  data() {
    return {
      breadcrumbs: {
        home: {
          icon: 'pi pi-home',
          // route: { name: 'ProtectedWelcome' }, // todo: реализовать https://v3.primevue.org/breadcrumb/#router
          command: () => {
            this.$router.push({ name: 'ProtectedWelcome' });
          },
        },
        items: [
          {
            label: this.$t('entities.auth.label'),
            command: () => {
              this.$router.push({ name: 'Auth_Index' });
            },
          },
          {
            label: this.$t('entities.auth.entities.user.many'),
            command: () => {
              this.$router.push({ name: 'Auth_User_Search' });
            },
          },
          {
            label: this.$t('main.details'),
          },
        ]
      },
    }
  },

  computed: {
    fallbackFormPayload() {
      return {
        email: this.$route.query['email'],
      }
    }
  }
});
</script>
