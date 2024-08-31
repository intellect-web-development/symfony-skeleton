<template>
  <Card>
    <template #content>
      <DataTable
          v-if="hasPayload"
          :loading="loading"
          :value="payload"
          :reorderableColumns="true"
          :stripedRows="stripedRows"
          paginator
          :removableSort="true"
          class="dataTable"
          paginatorTemplate=""
          :sortField="sortField"
          :sortOrder="sortOrder"
          :rows="paginatorRows"
          filterDisplay="menu"
          v-model:filters="filters"
          :globalFilterFields="globalFilterFields"
          resizableColumns columnResizeMode="expand"
          lazy
          :rowsPerPageOptions="paginationRowSizes"
          @sort="onSort"
      >
        <template #header>
          <div class="dataTable__header">
            <span class="dataTable__header__label">
              {{ name }}
            </span>
            <Button class="dataTable__header__ml" icon="pi pi-filter-slash" severity="contrast" raised text @click="resetFilters" />
            <div class="dataTable__header__recordsFound">
              <i
                  v-badge.contrast="qPagination.rowsNumber"
                  v-tooltip.bottom="$t(tPath + 'records_found', {num: qPagination.rowsNumber})"
                  class="pi pi-search"
                  style="font-size: 1.5rem"
              />
            </div>
          </div>
        </template>

        <template #empty> Not found. </template>
        <template #loading> Loading customers data. Please wait. </template>
        <Column
            v-for="property of columns" :key="property.name"
            :field="property.name"
            :header="property.label"
            :sortable="true"
            :showFilterMatchModes="false"
        >
          <template #header>
            <template v-if="//@ts-ignore
                !!violations[property.name]">
              <Button
                  icon="pi pi-exclamation-triangle"
                  v-tooltip.top="//@ts-ignore
              violations[property.name]"
                  severity="warning"
                  text
                  rounded
              />
            </template>
          </template>
          <template #body="slotProps">
            <span v-if="property.detailsLink">
              <RouterLink :to="generateDetailsViewRoute(property.detailsLink.routeName, property.detailsLink.paramProperty ?? property.name, slotProps.data[property.detailsLink.paramProperty ?? property.name])">
                <Button icon="pi pi-link" :label="slotProps.data[property.name]" link />
              </RouterLink>
            </span>
            <span v-else>{{//@ts-ignore
            slotProps.data[property.name]
              }}</span>
          </template>

          <template #filter="{ filterModel, filterCallback }">
            <!-- filter mode select -->
            <Dropdown
                v-model="//@ts-ignore
                filterModel.searchMode"
                :options="searchModesArray"
                optionLabel="label"
                @update:model-value="//@ts-ignore
                searchPayload.filterCollection.filtersAssoc[property.name].searchMode = filterModel.searchMode.value;"
                filter
            />

            <!-- input -->
            <InputText
                v-if="filterModel && filterInputIsInput(searchPayload.filterCollection.filtersAssoc[property.name].searchMode)"
                v-model="filterModel.value"
                type="text"
                @update:model-value="searchPayload.filterCollection.filtersAssoc[property.name].value = filterModel.value;"
                class="p-column-filter"
                placeholder="Search"
                @keydown="runCallbackOnPressEnter($event, [filterCallback, onFilterApply])"
            />
            <!-- range -->
            <template v-else-if="filterInputIsRange(searchPayload.filterCollection.filtersAssoc[property.name].searchMode)">
              <RangeInput
                  :hint="property.name + ' ' + searchPayload.filterCollection.filtersAssoc[property.name].searchMode"
                  :value="searchPayload.filterCollection.filtersAssoc[property.name].value"
                  @changeValue="(v) => {searchPayload.filterCollection.filtersAssoc[property.name].value = v; onFilterApply()}"
              />
            </template>
            <!-- multiSelect -->
            <Dropdown
                v-else-if="filterModel && filterInputIsMultiSelect(searchPayload.filterCollection.filtersAssoc[property.name].searchMode)"
                v-model="filterModel.value"
                @change="searchPayload.filterCollection.filtersAssoc[property.name].value = filterModel.value?.value;"
                :options="property.filter?.states"
                optionLabel="label"
                :editable="property.filter?.editable ?? true"
                showClear
            />
            <!-- toggle -->
            <div
                v-else-if="filterInputIsToggle(searchPayload.filterCollection.filtersAssoc[property.name].searchMode)"
                style="display: flex; justify-content: center; margin-top: 1.5rem"
            >
              <InputSwitch
                  v-model="filterModel.value"
                  @update:model-value="searchPayload.filterCollection.filtersAssoc[property.name].value = filterModel.value;"
              />
            </div>
            <!-- fallback -->
            <InputText
                v-else
                v-model="filterModel.value"
                type="text"
                @update:model-value="searchPayload.filterCollection.filtersAssoc[property.name].value = filterModel.value;"
                class="p-column-filter"
                placeholder="Search"
                @keydown="runCallbackOnPressEnter($event, [filterCallback, onFilterApply])"
            />
          </template>

          <template #filterapply="{ filterCallback }">
            <Button
                label="Поиск"
                outlined
                raised
                icon="pi pi-search"
                severity="info"
                @click="filterCallback(); onFilterApply();"
            />
          </template>
          <template #filterclear>
            <div></div>
          </template>
        </Column>

        <template #paginatorstart>
          <Button icon="pi pi-refresh" text @click="onSearch" v-tooltip="'Обновить'" />
        </template>
        <template #paginatorend>
        </template>

        <template #footer>
          <div style="display:flex; justify-content: center;">
            <Paginator
                v-model:first="paginatorOffset"
                :rows="searchPayload.pagination.size"
                :totalRecords="qPagination.rowsNumber"
                :rowsPerPageOptions="paginationRowSizes"
                @page="onChangePageInPaginator($event)"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
            >
            </Paginator>
          </div>
        </template>
      </DataTable>
    </template>
  </Card>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import Pagination from "@/api/common/filter/pagination/Pagination";
import SortCollection from "@/api/common/filter/sort/SortCollection";
import FilterCollection from "@/api/common/filter/filter/FilterCollection";
import SearchMode from "@/api/common/filter/constants/SearchMode";
import Properties from "@/api/common/filter/dto/Properties";
import RangeInput from "@/components/Search/SearchModule/RangeInput.vue";
import debounce from "debounce";
import type Property from "@/api/common/filter/dto/Property";
import Sort from "@/api/common/filter/sort/Sort";
import Filter from "@/api/common/filter/filter/Filter";
import {Strategy} from "@/api/common/filter/strategy/Strategy";
import {validate} from "@/api/common/validator";

// todo: Произвести рефакторинг SearchModule, отпилить все лишнее, причесать код, дореализовать недостающие элементы, отмеченные todo'шками. Приемка: Код отрефакторен, готов к тирражированию в кодогенераторе
// todo: Добавить все необходимые переводы на компонент SearchModule
// todo: Реализовать возможность фильтровать по полям соседних сущностей(для этого скорее всего придется организовать вложенные модели, типо account.operations.id).
// Объединить в рабочую связку систему, если в поле OutputContract и название фильтра различаются: "accountId, account.id"
// todo: Добавить возможность ограничивать по числу символов строку для таблицы, для отдельной ячейки таблицы (актуально, если работаем с типом "Text", и там томик Войны и Мира)
// todo: Проверить issue: невозможно выводить вложенные значения свойств (example: property.detail.id)
export default defineComponent({
  components: { RangeInput },

  created() {
    this.onSearch = debounce(this.onSearch, 500);

    this.initSearchPayload();
    this.paginatorOffset = this.searchPayload.pagination.size * this.searchPayload.pagination.number - 1;
    this.onFilterApply();

    this.properties.properties.map((property: Property) => {
      if (!this.searchPayload.filterCollection.filtersAssoc[property.name]) {
        return;
      }
      let searchMode = this.searchPayload.filterCollection.filtersAssoc[property.name].searchMode;
      // @ts-ignore
      this.filters[property.name] = {
        value: this.searchPayload.filterCollection.filtersAssoc[property.name].value,
        searchMode: { value: searchMode, label: this.$t(this.tPathSearchModes + searchMode) },
      };
    });
  },

  data() {
    return {
      tPathSearchModes: 'components.search.search_modes.',
      tPath: 'components.search.search_module.',
      filters: {},
      paginatorOffset: 0,
      paginatorRows: 20,
      // size: { label: 'Normal', value: 'null' },
      // sizeOptions: [
      //   { label: 'S', value: 'small' },
      //   { label: 'M', value: 'null' },
      //   { label: 'L', value: 'large' }
      // ], // todo: убрать в props
      searchPayload: {
        lang: '',
        filterCollection: new FilterCollection([]),
        sortCollection: new SortCollection([]),
        pagination: new Pagination(1, 20),
        strategy: Strategy.And,
      },
      filterBarIsOpen: true,
      qPagination: {
        rowsNumber: undefined,
      }, // todo: есть ощущение, что тут можно очень сильно сократить содержимое переменной
      filterSettingsDialog: false,
      loading: false,
      searchResult: {},
    }
  },

  props: {
    name: {
      required: true,
      type: String,
    },
    style: {
      required: false,
      default: []
    },
    urlDriven: {
      required: false,
      type: Boolean,
      default: true
    },
    properties: {
      required: true,
      type: Properties,
    },
    startFilters: {
      required: false,
      type: FilterCollection,
      default: new FilterCollection([])
    },
    searchMethod: {
      required: true,
    },
    searchRequest: {
      required: true,
      type: Function
    },
    stripedRows: {
      required: false,
      type: Boolean,
      default: true,
    },
    pageSizeDefault: {
      required: false,
      type: Number,
      default: 20,
    },
  },
  emits: [ "searchFulfilled" ],

  computed: {
    searchModesArray() {
      return [
        {
          value: SearchMode.EQUALS,
          label: this.$t(this.tPathSearchModes + SearchMode.EQUALS),
        },
        {
          value: SearchMode.NOT_EQUALS,
          label: this.$t(this.tPathSearchModes + SearchMode.NOT_EQUALS),
        },
        {
          value: SearchMode.LIKE,
          label: this.$t(this.tPathSearchModes + SearchMode.LIKE),
        },
        {
          value: SearchMode.NOT_LIKE,
          label: this.$t(this.tPathSearchModes + SearchMode.NOT_LIKE),
        },
        {
          value: SearchMode.IS_NULL,
          label: this.$t(this.tPathSearchModes + SearchMode.IS_NULL),
        },
        {
          value: SearchMode.NOT_NULL,
          label: this.$t(this.tPathSearchModes + SearchMode.NOT_NULL),
        },
        {
          value: SearchMode.IN,
          label: this.$t(this.tPathSearchModes + SearchMode.IN),
        },
        {
          value: SearchMode.NOT_IN,
          label: this.$t(this.tPathSearchModes + SearchMode.NOT_IN),
        },
        {
          value: SearchMode.RANGE,
          label: this.$t(this.tPathSearchModes + SearchMode.RANGE),
        },
        {
          value: SearchMode.LESS_THAN,
          label: this.$t(this.tPathSearchModes + SearchMode.LESS_THAN),
        },
        {
          value: SearchMode.LESS_OR_EQUALS,
          label: this.$t(this.tPathSearchModes + SearchMode.LESS_OR_EQUALS),
        },
        {
          value: SearchMode.GREATER_THAN,
          label: this.$t(this.tPathSearchModes + SearchMode.GREATER_THAN),
        },
        {
          value: SearchMode.GREATER_OR_EQUALS,
          label: this.$t(this.tPathSearchModes + SearchMode.GREATER_OR_EQUALS),
        },
      ];
    },
    sortField() {
      let sorts = this.searchPayload.sortCollection.sorts;

      if (sorts.length === 0) {
        return undefined;
      }

      return sorts[sorts.length - 1].field;
    },
    sortOrder() {
      let sorts = this.searchPayload.sortCollection.sorts;

      if (sorts.length === 0) {
        return 0;
      }

      if (sorts[sorts.length - 1].direction === 'asc') {
        return 1;
      }
      if (sorts[sorts.length - 1].direction === 'desc') {
        return -1;
      }

      return 0;
    },
    paginationRowSizes() {
      return Array.from(new Set([this.searchPayload.pagination.size, 10, 20, 50, 100]));
    },
    hasPayload() {
      return '{}' !== JSON.stringify(this.searchResult);
    },
    payload() {
      //@ts-ignore
      let itemPreviewFunctions: Function[] = {};
      this.properties.properties.map((property: Property) => {
        if (property.previewFn) {
          //@ts-ignore
          itemPreviewFunctions[property.name] = property.previewFn;
        }
      });

      //@ts-ignore
      return this.searchResult.data.data.map((item) => {
        let cloneItem = {...item};
        for (let property in cloneItem) {
          let value = item[property];
          if (itemPreviewFunctions.hasOwnProperty(property)) {
            //@ts-ignore
            cloneItem[property] = itemPreviewFunctions[property](value);
          }
        }
        return cloneItem;
      });
    },
    violations(): object {
      let validationPayload = {};
      let validationSchemaFields = this.properties.validateSchema.fields;

      for (let i in this.filterableProperties) {
        let filterableProperty = this.filterableProperties[i];
        if (!this.searchPayload.filterCollection.filtersAssoc[filterableProperty.name].value) {
          continue;
        }
        let property = this.searchPayload.filterCollection.filtersAssoc[filterableProperty.name];

        if (validationSchemaFields.hasOwnProperty(filterableProperty.name)) {
          // let isValid;
          if ([SearchMode.IN, SearchMode.NOT_IN, SearchMode.RANGE].includes(property.searchMode)) {
            // todo: Пока что игнорируем валидацию для этого типа, ибо на текущий момент не критично.
            // Потом нужно будет придумать каким образом заставить yup валидировать несколько значений.
          } else {
            //@ts-ignore
            validationPayload[filterableProperty.name] = property.value;
          }
        }
      }

      let violations = validate(this.properties.validateSchema, validationPayload);

      return violations.violations;
    },
    hasViolations() {
      return JSON.stringify(this.violations) !== '{}'
    },
    globalFilterFields() {
      let columns: any = [];

      this.properties.properties.map((property: Property) => {
        columns.push(property.name)
      })

      return columns;
    },
    columns() {
      return this.properties.properties;
    },
    searchResultIsInit(): boolean {
      return JSON.stringify(this.searchResult) !== '{}';
    },
    filterableProperties(): Property[] {
      return this.properties.properties.filter((property: Property) => {
        return !!property.filter;
      });
    },
  },

  watch: {
    searchResult() {
      if (this.searchResultIsInit) {
        // @ts-ignore
        this.qPagination.page = this.searchResult.data.pagination.page;
        // @ts-ignore
        this.qPagination.rowsPerPage = this.searchResult.data.pagination.size;
        // @ts-ignore
        this.qPagination.rowsNumber = this.searchResult.data.pagination.count;
        // @ts-ignore
        this.qPagination.sortBy = this.searchPayload.sortCollection.sorts.length > 0 ? this.searchPayload.sortCollection.sorts[0].field : null;
        // @ts-ignore
        this.qPagination.descending = this.searchPayload.sortCollection.sorts.length > 0 ? this.searchPayload.sortCollection.sorts[0].direction === 'desc' : false;
      }
    },
  },

  methods: {
    generateDetailsViewRoute(routeName: string, propertyName: string, propertyValue: string): object {
      let params = {};
      // @ts-ignore
      params[propertyName] = propertyValue;

      return {name: routeName, params: params};
    },
    runCallbackOnPressEnter(event: KeyboardEvent, callbacks: Array<Function>) {
      if (event.key === "Enter") {
        callbacks.map((callback: Function) => {
          callback();
        })
      }
    },
    onSort(event: any) {
      let sortField = event.sortField;
      let sortOrder = event.sortOrder;

      let sortCollection = new SortCollection([]);

      if (sortOrder === 1) {
        sortCollection.sorts.push(new Sort(
            sortField,
            'asc'
        ));
      }
      if (sortOrder === -1) {
        sortCollection.sorts.push(new Sort(
            sortField,
            'desc'
        ));
      }
      this.searchPayload.sortCollection = sortCollection
      this.onFilterApply()
    },
    filterInputIsInput(type: SearchMode) {
      return [
        SearchMode.LIKE,
        SearchMode.EQUALS,
        SearchMode.NOT_LIKE,
        SearchMode.LESS_THAN,
        SearchMode.NOT_EQUALS,
        SearchMode.GREATER_THAN,
        SearchMode.LESS_OR_EQUALS,
        SearchMode.GREATER_OR_EQUALS,
      ].includes(type)
    },
    filterInputIsMultiSelect(type: SearchMode) {
      return [
        SearchMode.IN,
        SearchMode.NOT_IN,
      ].includes(type)
    },
    filterInputIsToggle(type: SearchMode) {
      return [
        SearchMode.IS_NULL,
        SearchMode.NOT_NULL,
      ].includes(type)
    },
    filterInputIsRange(type: SearchMode) {
      return [
        SearchMode.RANGE
      ].includes(type)
    },
    loadLangFromURI(): string {
      return this.$route.query.lang?.toString() ?? navigator.language.split('-')[0].toString();
    },
    loadPaginationFromURI() {
      return new Pagination(
          parseInt(this.$route.query['page[number]']?.toString() ?? '1'),
          parseInt(this.$route.query['page[size]']?.toString() ?? '20'),
      )
    },
    loadSortsFromURI(): Sort[] {
      let sorts: Sort[] = [];
      if (this.$route.query.sort) {
        // @ts-ignore
        this.$route.query.sort.split(',').map((property: string) => {
          sorts.push(
              new Sort(
                  property.replace('-', ''),
                  property[0] === '-' ? 'desc' : 'asc'
              )
          )
        })
      }

      return sorts;
    },
    loadFiltersFromURI(): Filter[] {
      let splitter = 'o_O';
      let filters = [];
      let filterRegex = new RegExp('filter', 'i');
      for (let param in this.$route.query) {
        if (filterRegex.test(param)) {
          // @ts-ignore
          let parseParam = param.replaceAll('[', splitter)
              .replaceAll(']', '')
              .split(splitter)
          let property = parseParam[1];
          let searchMode = parseParam[2];
          filters.push(
              new Filter(
                  property,
                  searchMode,
                  // @ts-ignore
                  this.$route.query[param]
              )
          );
        }
      }

      return filters;
    },
    loadFiltersFromProps(): Filter[] {
      // @ts-ignore
      return this.properties.properties.map((property: Property) => {
        if (property.filter) {
          return new Filter(
              property.name,
              property.filter.type,
              null
          )
        }
      }).filter((filter: Filter|undefined) => {
        return !!filter
      });
    },
    initSearchPayload() {
      let filtersObject = {};
      [...this.loadFiltersFromProps(), ...this.loadFiltersFromURI(), ...this.startFilters.filters]
          .forEach((filter: Filter) => {
            // @ts-ignore
            filtersObject[filter.property] = filter;
          });

      this.searchPayload.lang = this.loadLangFromURI();
      this.searchPayload.pagination = this.loadPaginationFromURI();
      // @ts-ignore
      this.searchPayload.filterCollection = new FilterCollection(Object.values(filtersObject));
      this.searchPayload.sortCollection = new SortCollection(this.loadSortsFromURI());
    },
    onUpdateUrl() {
      if (!this.urlDriven) {
        return;
      }
      let queryParams: any = {
        "page[number]": this.searchPayload.pagination.number,
        "page[size]": this.searchPayload.pagination.size,
        lang: this.searchPayload.lang,
      };
      this.searchPayload.filterCollection.filters
          .filter((filter: Filter) => {
            return filter.value !== null
          })
          .map((filter: Filter) => {
            queryParams[`filter[${filter.property}][${filter.searchMode}]`] = filter.value;
          })
      if (this.searchPayload.sortCollection.sorts.length > 0) {
        queryParams['sort'] = this.searchPayload.sortCollection.toParams();
      }

      this.$router.replace({
        query: {
          ...queryParams
        }
      }).catch(() => {})
    },
    onSearch() {
      this.loading = true;
      // @ts-ignore
      this.searchMethod(
          // @ts-ignore
          new this.searchRequest(
              this.searchPayload.filterCollection,
              this.searchPayload.sortCollection,
              this.searchPayload.pagination,
              this.searchPayload.lang,
              this.searchPayload.strategy,
          )
      ).then((outputContract: any) => {
        this.searchResult = outputContract;
        this.$emit('searchFulfilled')
      }).catch((error: Error) => {
        console.log(error)
        // defaultErrorNotify(error.message) //todo: here
      }).finally(() => {
        this.loading = false;
      })
    },
    onChangePageInPaginator(event: any) {
      this.searchPayload.pagination.number = event.page + 1;
      this.searchPayload.pagination.size = event.rows;

      // this.searchPayload.pagination.number = page;
      // this.searchPayload.pagination.size = rowsPerPage;
      // if (sortBy !== null) {
      //   this.searchPayload.sortCollection = new SortCollection([
      //     new Sort(
      //         sortBy,
      //         descending ? 'desc' : 'asc'
      //     )
      //   ])
      // } else {
      //   this.searchPayload.sortCollection = new SortCollection([])
      // }

      this.onFilterApply()
    },
    onFilterApply() {
      if (!this.hasViolations) {
        this.onUpdateUrl()
        this.onSearch()
      } else {
        console.log('Invalid filters', this.violations, this.filters);
        //todo: here
        // Notify.create({
        //   color: 'red-5',
        //   icon: 'warning',
        //   message: 'Invalid filters',
        //   badgeStyle: 'display: none',
        //   timeout: 5000,
        //   actions: [
        //     {
        //       label: 'Violations',
        //       color: 'white',
        //       handler: () => {
        //         this.openDialogViolation()
        //       }
        //     },
        //     {
        //       label: 'Clear',
        //       color: 'white',
        //       handler: () => {
        //         this.resetFilters()
        //       }
        //     },
        //   ]
        // })
      }
    },
    resetFilters() {
      this.$router.replace({
        query: {}
      }).catch(() => {})
      for (let filter in this.searchPayload.filterCollection.filters) {
        // @ts-ignore
        this.searchPayload.filterCollection.filters[filter].value = null;
      }
      this.searchPayload.pagination.number = 1;
      this.searchPayload.pagination.size = this.pageSizeDefault;
      this.searchPayload.sortCollection = new SortCollection([]);
      this.onFilterApply()
    },
  },
})
</script>

<style lang="scss" scoped>
.dataTable {
  &__header{
    display: flex;
    align-items: center;
    &__label{
      font-size: 1.5rem;
    }
    &__recordsFound{
      margin: 1.5rem;
    }
    &__ml{
      margin-left: auto;
    }
  }
}
</style>