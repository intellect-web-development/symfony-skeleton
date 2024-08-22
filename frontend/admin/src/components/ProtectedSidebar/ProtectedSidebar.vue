<template>
  <Sidebar v-model:visible="visibleModel">
    <template #header>
      <Logo />
    </template>
    <template #default>
      <PanelMenu :model="items" />
    </template>
  </Sidebar>
</template>

<script lang="ts">
import Logo from "@/components/Common/Logo/Logo.vue";
import {default as items} from './schema/index'

export default {
  components: {Logo},
  props: {
    visible: {
      type: Boolean,
      default: false,
    },
  },

  emits: [ "visibleChanged" ],

  updated() {
      this.visibleModel = this.visible;
  },

  watch: {
    visibleModel() {
      this.emitChangeVisible();
    }
  },

  created() {
    this.items = this.translateItems(items);
  },

  methods: {
    translateItems(itemsArray: any) {
      return itemsArray.map((item: any) => {
        // Копируем оригинальный объект, чтобы не модифицировать его напрямую
        const translatedItem = { ...item };

        // Переводим label, если он существует
        if (translatedItem.label) {
          translatedItem.label = this.$t(translatedItem.label);
        }

        // Если есть вложенные элементы, переводим и их
        if (translatedItem.items && translatedItem.items.length > 0) {
          translatedItem.items = this.translateItems(translatedItem.items);
        }

        return translatedItem;
      });
    },
    emitChangeVisible() {
      this.$emit('visibleChanged', this.visibleModel)
    },
  },

  data() {
    return {
      visibleModel: false,
      items: [],
      tPath: 'nav.',
    };
  },
};
</script>

<style lang="scss" scoped>
</style>
