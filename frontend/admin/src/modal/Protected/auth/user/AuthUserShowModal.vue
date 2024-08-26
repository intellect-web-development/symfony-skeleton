<template>
  <Dialog v-model:visible="visibleModel" modal :header="$t(tPathEntity + 'actions.show')" :style="{ width: '50%', minWidth: '30rem' }">
    <AuthUserShowView
      :readonlyState="readonlyState"
    />
  </Dialog>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import AuthUserShowView from "@/views/Protected/auth/user/AuthUserShowView.vue";
import {AuthUserEntity} from "@/api/auth/user/AuthUserEntity";

export default defineComponent({
  components: {AuthUserShowView},
  emits: [ "visibleChanged" ],
  props: {
    visible: {
      type: Boolean,
      required: true,
    },
    readonlyState: {
      type: AuthUserEntity,
      required: true,
    }
  },
  created() {
    this.visibleModel = this.visible;
  },
  data() {
    return {
      visibleModel: false,
      tPathEntity: 'entities.auth.entities.user.',
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
