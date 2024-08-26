<template>
  <p>Auth</p>
<!--  <BreadCrumbs-->
<!--    :options="breadcrumbs"-->
<!--  />-->
<!--  <BoundedContextIndex-->
<!--    :bounded-context="boundedContext"-->
<!--    :entities="entities"-->
<!--  />-->
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import {useHead} from "unhead";
import {generateTitleFromBreadcrumbs} from "@/service/metaService";
// import BreadCrumbs from "components/BreadCrumbs.vue";
// import BoundedContextIndex from "components/pages/BoundedContextIndexPage.vue";

export default defineComponent({
  // components: { BoundedContextIndex, BreadCrumbs },

  created() {
    useHead({
      title: generateTitleFromBreadcrumbs(this.breadcrumbs)
    })
  },

  data() {
    return {
      // todo: реализовать индексную страницу контекста
      boundedContext: {
        name: 'Auth',
        description: 'Контур системы для идентификации и авторизации'
      },
      entities: [
        {
          name: '',
          description: '',
          toSearchPage: { name: '' }
        },
      ],
      breadcrumbs: {
        home: {
          icon: 'pi pi-home',
          command: () => {
            this.$router.push({ name: 'ProtectedWelcome' });
          },
        },
        items: [
          {
            label: this.$t('entities.auth.label'),
          },
        ]
      },
    }
  },
});
</script>
