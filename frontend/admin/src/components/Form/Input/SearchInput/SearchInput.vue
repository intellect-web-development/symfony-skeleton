<template>
  <Dropdown
      v-model="model"
      editable
      :options="options"
      :loading="loading"
      optionLabel="label"
      :emptyMessage="computedEmptyMessage"
      :placeholder="computedLabel"
      @change="onSearch($event.value)"
  >
  </Dropdown>
</template>

<script lang="ts">
import debounce from "debounce";
import SearchMode from "@/api/common/filter/constants/SearchMode";
import Filter from "@/api/common/filter/filter/Filter";
import FilterCollection from "@/api/common/filter/filter/FilterCollection";
import SortCollection from "@/api/common/filter/sort/SortCollection";
import Sort from "@/api/common/filter/sort/Sort";
import Pagination from "@/api/common/filter/pagination/Pagination";
import {Strategy} from "@/api/common/filter/strategy/Strategy";

export default {
  props: {
    label: {
      type: String,
      default: null,
    },
    emptyMessage: {
      type: String,
      default: null,
    },
    payload: {
      type: String,
      default: ''
    },
    // По этим полям будет идти поиск
    searchProperties: {
      type: Array<{ property: string, searchMode: SearchMode }>,
      required: true
    },
    // Если задано это поле, то будет показано то, что возвращает эта функция
    propertyLabelFn: {
      type: Function,
      required: true
    },
    targetValue: {
      type: String,
      required: true
    },
    searchMode: {
      type: String,
      default: SearchMode.LIKE
    },
    searchMethod: {
      type: Function,
      required: true
    },
    searchRequest: {
      required: true,
    },
    sortCollection: {
      type: SortCollection,
      default: new SortCollection([
        new Sort('id', 'asc')
      ]),
    },
    lang: {
      type: String,
      default: 'ru',
    },
    maxResults: {
      type: Number,
      default: 20,
      validator: function(value: number) {
        return value > 0;
      },
    },
  },

  created() {
    this.onSearch = debounce(this.onSearch, 500);
    this.onSearch(this.payload);
  },

  data() {
    return {
      tPath: 'components.form.input.search_input.',
      options: [],
      loading: false,
      model: '',
    }
  },

  computed: {
    computedLabel(): string {
      return this.label ?? this.$t(this.tPath + 'fallback_label');
    },
    computedEmptyMessage(): string {
      return this.emptyMessage ?? this.$t(this.tPath + 'empty_message');
    },
  },

  emits: [ "valueUpdated" ],

  methods: {
    onSearch(value: string) {
      if (typeof this.model === 'object') {
        return;
      }
      this.loading = true;

      let filters = [];
      for (let searchProperty of this.searchProperties) {
        filters.push(new Filter(searchProperty.property, searchProperty.searchMode, value));
      }
      let filterCollection = new FilterCollection(filters);

      this.searchMethod(
          //@ts-ignore
          new this.searchRequest(
              filterCollection,
              this.sortCollection,
              new Pagination(1, this.maxResults),
              this.lang,
              Strategy.Or
          )
      ).then((outputContract: any) => {
        let options: object[] = [];
        if (outputContract.data.hasOwnProperty('data')) {
          let total = outputContract.data['data'].length;
          // Если найдена только одна запись - подставить ее сразу в поле
          if (1 === total) {
            //@ts-ignore
            this.model = {
              label: this.propertyLabelFn(outputContract.data['data'][0]),
              //@ts-ignore
              value: outputContract.data['data'][0][this.targetValue],
            }
          }
          //@ts-ignore
          outputContract.data['data'].map((entity: object) => {
            options.push({
              label: this.propertyLabelFn(entity),
              //@ts-ignore
              value: entity[this.targetValue],
            })
          });

          //@ts-ignore
          this.options = options;
        }
      }).catch((error: Error) => {
        console.error(error)
        this.$toast.add({ severity: 'error', summary: this.$t('main.error'), detail: this.$t(error.message), life: 5000 });
      }).finally(() => {
        this.loading = false;
      })
    },

    emitChange() {
      if (typeof this.model === 'object') {
        //@ts-ignore
        this.$emit('valueUpdated', this.model.value)
      }
    },
  },
  watch: {
    model() {
      this.emitChange();
    }
  },
}
</script>

<style lang="scss" scoped>
</style>
