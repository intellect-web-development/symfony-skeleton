<template>
  <div class="h-100">
    <div class="container">
      <Breadcrumb :home="breadcrumbs.home" :model="breadcrumbs.items" />
      <SearchPage
          :name="entityName"
          :properties="properties"
          :search-method="searchMethod"
          :search-request="searchRequest"
      />
    </div>
    <SpeedDial
        :model="commands"
        direction="up"
        :radius="120"
        :tooltipOptions="{ position: 'left' }"
        :style="{ bottom: '2rem', right: '2rem'}"
        :transitionDelay="50"
        showIcon="pi pi-bars"
        hideIcon="pi pi-times"
    >
    </SpeedDial>
    <AuthUserCreateModal
        :visible="createDialogVisible"
        @visibleChanged="createDialogVisible = $event"
    />
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue';
import SearchPage from "@/components/Search/SearchPage/SearchPage.vue";
import {useAuthUserStore} from "@/stores/auth/authUserStore";
import AuthUserSearchRequest from "@/api/auth/user/methods/search/AuthUserSearchRequest";
import AuthUserCreateModal from "@/modal/Protected/auth/user/AuthUserCreateModal.vue";
import Properties from "@/api/common/filter/dto/Properties";
import Property from "@/api/common/filter/dto/Property";
import PropertyFilter from "@/api/common/filter/dto/PropertyFilter";
import SearchMode from "@/api/common/filter/constants/SearchMode";
import * as yup from "yup";
import DetailsLink from "@/api/common/filter/dto/DetailsLink";
import {dateOrDatetime} from "@/api/common/regex";
import {useHead} from "unhead";
import {generateTitleFromBreadcrumbs} from "@/service/metaService";

export default defineComponent({
  components: {AuthUserCreateModal, SearchPage},

  created() {
    useHead({
      title: generateTitleFromBreadcrumbs(this.breadcrumbs)
    })
  },

  data() {
    return {
      tPathEntity: 'entities.auth.entities.user.',
      createDialogVisible: false,
      breadcrumbs: {
        home: {
          icon: 'pi pi-home',
          command: () => {
            this.$router.push({ name: 'ProtectedWelcome' });
          },
        },
        items: [
          {
            label: this.$t('entities.auth.label'),
            command: () => {
              this.$router.push({ name: 'Auth_Index' });
            },
          },
          {
            label: this.$t('entities.auth.entities.user.many'),
          },
        ]
      },
      commands: [
        {
          label: this.$t(this.tPathEntity + 'actions.create'),
          icon: 'pi pi-plus-circle',
          command: () => {
            this.openCreateDialog();
          }
        },
      ],
    }
  },

  methods: {
    openCreateDialog() {
      this.createDialogVisible = true;
    },
  },

  computed: {
    entityName() {
      return this.$t(this.tPathEntity + 'one');
    },

    searchMethod() {
      return useAuthUserStore().search
    },
    searchRequest() {
      return AuthUserSearchRequest;
    },
    properties(): Properties {
      return new Properties([
        new Property(
          'id',
          this.$t(this.tPathEntity + 'properties.id'),
          new PropertyFilter({
            type: SearchMode.EQUALS,
            validation: yup.number(),
          }),
          null,
          new DetailsLink('Auth_User_Details')
        ),
        new Property(
          'createdAt',
          this.$t(this.tPathEntity + 'properties.created_at'),
          new PropertyFilter({
            type: SearchMode.RANGE,
            validation: yup.string().matches(dateOrDatetime(), 'createdAt format YYYY-MM-DD HH:MM:SS')
          }),
          (value: string) => {
            let date = new Date(value);

            return date.toLocaleString(undefined, {
              year: 'numeric',
              month: 'long',
              day: 'numeric',
              minute: 'numeric',
              hour: 'numeric',
            });
          },
          null,
        ),
        new Property(
          'updatedAt',
          this.$t(this.tPathEntity + 'properties.updated_at'),
          new PropertyFilter({
            type: SearchMode.RANGE,
            validation: yup.string().matches(dateOrDatetime(), 'updatedAt format YYYY-MM-DD HH:MM:SS')
          }),
          (value: string) => {
            let date = new Date(value);

            return date.toLocaleString(undefined, {
              year: 'numeric',
              month: 'long',
              day: 'numeric',
              minute: 'numeric',
              hour: 'numeric',
            });
          },
          null,
        ),
        new Property(
          'email',
          this.$t(this.tPathEntity + 'properties.email'),
          new PropertyFilter({
            type: SearchMode.LIKE,
          }),
          null,
          null,
        ),
      ]);
    }
  },
});
</script>

<style lang="scss" scoped>
</style>
