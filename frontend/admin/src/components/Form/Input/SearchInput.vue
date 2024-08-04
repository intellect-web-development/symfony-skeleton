<template>
  <Dropdown
      v-model="model"
      editable
      :options="options"
      :loading="loading"
      optionLabel="label"
      emptyMessage="Нет данных..."
      :placeholder="label"
      @change="onSearch($event.value)"
  >
  </Dropdown>
</template>

<script lang="ts">
import debounce from "debounce";
import SearchModes from "@/api/common/filter/constants/searchModes";
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
      default: 'Search...'
    },
    payload: {
      type: String,
      default: ''
    },
    // По этим полям будет идти поиск
    searchProperties: {
      type: Array<{ property: string, searchMode: string }>,
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
      default: SearchModes.LIKE
    },
    searchMethod: {
      type: Function,
      required: true
    },
    searchRequest: {
      // type: BaseSearchRequest,
      required: true,
    }
  },

  created() {
    this.model = this.payload;
    this.onSearch = debounce(this.onSearch, 500);
    this.onSearch('');
  },

  data() {
    return {
      options: [],
      loading: false,
      model: '',
    }
  },

  emits: [ "valueUpdated" ],

  methods: {
    onSearch(value: string) {
      if (typeof this.model === 'object') {
        return;
      }

      let filters = [];
      for (let searchProperty of this.searchProperties) {
        filters.push(new Filter(searchProperty.property, searchProperty.searchMode, value));
      }
      let filterCollection = new FilterCollection(filters);
      this.searchMethod(
          //@ts-ignore
          new this.searchRequest(
              filterCollection,
              new SortCollection([
                new Sort('id', 'asc')
              ]),
              new Pagination(1, 20),
              'ru',
              Strategy.Or
          )
      ).then((outputContract: any) => {
        let options: object[] = [];
        if (outputContract.data.hasOwnProperty('data')) {
          //@ts-ignore
          outputContract.data['data'].map((entity: object) => {
            options.push({
              label: this.propertyLabelFn(entity),
              //@ts-ignore
              value: entity[this.targetValue],
              // description: 'Search engine',
              // icon: 'mail',
              // disable: true,
            })
          });

          //@ts-ignore
          this.options = options;
        }
      }).catch((error: Error) => {
        console.error(error)
        // todo: как-то обработать ошибку
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
