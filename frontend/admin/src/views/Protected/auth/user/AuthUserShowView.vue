<template>
  <Card class="card" unstyled>
    <template #title>
      <span class="card__title">{{$t(tPathEntity + 'one')}} #{{ id }}</span>
    </template>
    <template #content>
      <div class="row-main">
        <div class="items">
          <SimpleItem :label="$t(tPathEntity + 'properties.created_at')">{{ createdAt }}</SimpleItem>
          <SimpleItem :label="$t(tPathEntity + 'properties.updated_at')">{{ updatedAt }}</SimpleItem>
          <SimpleItem :label="$t(tPathEntity + 'properties.email')">{{ email }}</SimpleItem>
        </div>
      </div>
    </template>
  </Card>

</template>

<script lang="ts">
import { defineComponent } from 'vue';
import {AuthUserEntity} from "@/api/auth/user/AuthUserEntity";
import SimpleItem from "@/components/DetailsItem/Simple/SimpleItem.vue";

export default defineComponent({
  components: { SimpleItem },
  props: {
    readonlyState: {
      type: AuthUserEntity,
      required: true
    }
  },
  data() {
    return {
      tPathEntity: 'entities.auth.entities.user.',
    };
  },
  computed: {
    id() {
      return this.readonlyState.id;
    },
    createdAt() {
      //@ts-ignore
      let date = new Date(this.readonlyState.createdAt);

      return date.toLocaleString(undefined, {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        minute: 'numeric',
        hour: 'numeric',
      });
    },
    updatedAt() {
      //@ts-ignore
      let date = new Date(this.readonlyState.updatedAt);

      return date.toLocaleString(undefined, {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        minute: 'numeric',
        hour: 'numeric',
      });
    },
    email() {
      return this.readonlyState.email;
    },
  },
});
</script>

<style lang="scss" scoped>
.card {
  &__title {
    display: block;
    font-size: 1.5em;
    font-weight: bold;
    margin-bottom: 15px;
    padding-bottom: 5px;
  }
}
</style>