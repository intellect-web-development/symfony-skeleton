<template>
  <q-page class="row items-center justify-evenly">
    Hello
  </q-page>
</template>

<script>
import { defineComponent } from 'vue';
import { useMeta } from "quasar";

export default defineComponent({
  beforeCreate() {
    useMeta(() => {
      return {
        title: 'Admin panel'
      }
    })
  }
});
</script>
