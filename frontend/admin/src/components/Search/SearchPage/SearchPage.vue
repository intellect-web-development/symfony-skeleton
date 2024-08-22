<template>
  <PageLoader
      v-if="!searchFulfilled"
      :label="tPath + 'loading'"
  />
  <div v-show="searchFulfilled">
    <SearchModule
        :name="name"
        :properties="properties"
        :search-method="searchMethod"
        :search-request="searchRequest"
        :url-driven="urlDriven"
        @searchFulfilled="onSearchFulfilled"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import SearchModule from "@/components/Search/SearchModule/SearchModule.vue";
import PageLoader from "@/components/Common/PageLoader/PageLoader.vue";
import Properties from "@/api/common/filter/dto/Properties";

export default defineComponent({
  components: {PageLoader, SearchModule},
  data() {
    return {
      searchFulfilled: false,
      tPath: 'components.search.search_page.',
    }
  },

  props: {
    name: {
      required: true,
      type: String,
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
    searchMethod: {
      required: true,
    },
    searchRequest: {
      required: true,
      type: Function
    }
  },

  methods: {
    onSearchFulfilled() {
      this.searchFulfilled = true
    }
  }
});
</script>
