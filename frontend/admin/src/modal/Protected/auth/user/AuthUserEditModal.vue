<template>
  <Dialog v-model:visible="visibleModel" modal :header="$t(tPathEntity + 'actions.edit')" :style="{ width: '50%', minWidth: '30rem' }">
    <AuthUserEditView
      :readonlyState="readonlyState"
      @readonlyStateChanged="onReadonlyStateChanged"
    />
  </Dialog>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import AuthUserEditView from "@/views/Protected/auth/user/AuthUserEditView.vue";
import {AuthUserEntity} from "@/api/auth/user/AuthUserEntity";

export default defineComponent({
  components: {AuthUserEditView},
  emits: [ "visibleChanged", 'readonlyStateChanged' ],
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
  methods: {
    onReadonlyStateChanged(state: AuthUserEntity) {
      this.$emit('readonlyStateChanged', state)
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
