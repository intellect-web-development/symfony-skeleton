<template>
  <InputText
      type="text"
      class="p-column-filter"
      :placeholder="`${hint} ${fromPlaceholder}`"
      style="min-width: 10rem"
      v-model="payload.from"
      @change:model-value="emitChange"
  />
  <InputText
      type="text"
      class="p-column-filter"
      :placeholder="`${hint} ${toPlaceholder}`"
      style="min-width: 10rem"
      v-model="payload.to"
      @change:model-value="emitChange"
  />
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  created() {
    if (!this.value) {
      this.payload.from = '';
      this.payload.to = '';
    } else {
      let splitValue = this.value.split(',')
      if (splitValue.length !== 2) {
        this.payload.from = '';
        this.payload.to = '';
      } else {
        this.payload.from = splitValue[0];
        this.payload.to = splitValue[1];
      }
    }
  },
  computed: {
    fromIsClear(): boolean {
      return this.payload.from === '' || this.payload.from === null
    },
    toIsClear(): boolean {
      return this.payload.to === '' || this.payload.to === null
    },
    inputIsClear(): boolean {
      return this.fromIsClear && this.toIsClear;
    },
    inputIsNotClear(): boolean {
      return !this.fromIsClear && !this.toIsClear;
    },
    fromAndToIsNotEquals(): boolean {
      return this.payload.from !== this.payload.to;
    },
  },
  props: {
    value: {
      type: String,
      default: null
    },
    hint: {
      type: String,
      default: ''
    },
    fromPlaceholder: {
      type: String,
      default: 'from'
    },
    toPlaceholder: {
      type: String,
      default: 'to'
    }
  },
  emits: [ "changeValue" ],
  data() {
    return {
      payload: {
        from: '',
        to: '',
      }
    }
  },
  methods: {
    emitChange() {
      if (this.inputIsNotClear && this.fromAndToIsNotEquals) {
        this.$emit('changeValue', `${this.payload.from},${this.payload.to}`)
      }
      if (this.inputIsClear) {
        this.$emit('changeValue', null)
      }
    }
  }
});
</script>
