<template>
   <Dialog v-model:visible="visibleModel" modal :header="$t(tPathEntity + 'actions.create')" :style="{ width: '50%', minWidth: '30rem' }">
    <AuthUserCreateView />
    <template #footer>
      <RouterLink :to="{name: 'Auth_User_Create'}" style="margin-right: auto">
        <Button v-tooltip.left="$t(tPathMain + 'go_to_full_form')" icon="pi pi-external-link" outlined severity="secondary" />
      </RouterLink>
    </template>
  </Dialog>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import AuthUserCreateView from "@/views/Protected/auth/user/AuthUserCreateView.vue";

export default defineComponent({
  components: {AuthUserCreateView},
  emits: [ "visibleChanged" ],
  props: {
    visible: {
      type: Boolean,
      required: true,
    },
  },
  created() {
    this.visibleModel = this.visible;
  },
  data() {
    return {
      visibleModel: false,
      tPathEntity: 'entities.auth.entities.user.',
      tPathMain: 'main.',
    }
  },
  watch: {
    visible() {
      this.visibleModel = this.visible;
    },
    visibleModel() {
      this.$emit('visibleChanged', this.visibleModel)
    },
  },
});
</script>

<style lang="scss" scoped>
</style>
