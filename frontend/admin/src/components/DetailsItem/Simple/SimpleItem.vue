<template>
  <div class="simpleItem">
    <div class="simpleItem__label">
      <slot name="label">{{ label }}</slot>
      <span class="simpleItem__divider">
        <slot name="divider">:</slot>
      </span>
    </div>
    <div class="simpleItem__value">
      <slot>{{ value }}</slot>
    </div>
  </div>
</template>

<script lang="ts">

export default {
  props: {
    label: {
      type: String,
    },
    value: {
      type: String,
    },
  },

  data() {
    return {
      // tPath: 'components.form.input.',
    }
  },
}
</script>

<style lang="scss" scoped>
.simpleItem {
  display: flex;
  align-items: center; /* Вертикальное выравнивание элементов */
  background-color: rgba(255, 255, 255, 0.1); /* Легкий полупрозрачный фон для выделения */
  padding: 10px; /* Отступ вокруг элементов */
  border-radius: 5px; /* Скругление углов */
  margin-bottom: 0.1rem; /* Отступ между компонентами */
  box-shadow: none; /* Уберите тень или используйте очень легкую */

  &__label {
    font-weight: bold; /* Жирное начертание для метки */
    color: #e0e0e0; /* Мягкий цвет метки для лучшего контраста на темном фоне */
    margin-right: 8px; /* Отступ между меткой и значением */
  }

  &__divider {
    margin: 0 1px; /* Отступы для разделителя */
  }

  &__value {
    font-size: 14px; /* Размер шрифта для значения */
    color: #f5f5f5; /* Цвет значения, чтобы выделялся на темном фоне */
    margin-left: auto;
  }
}
</style>
