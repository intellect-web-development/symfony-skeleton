<template>
  <div class="container">
    <Breadcrumb :home="breadcrumbs.home" :model="breadcrumbs.items" />
    <PageLoader v-if="!modelInit" />
    <div v-else>
      <BlockUI :blocked="locked">
        <Card>
          <template #header>
          </template>
          <template #content>
            <div class="row-main">
              <AuthUserShowView class="show" :readonlyState="readonlyState" />
              <div class="sidebar"></div>
            </div>
          </template>
          <template #footer>
            <ButtonGroup>
              <Button icon="pi pi-file-edit" :label="$t(tPathMain + 'edit')" severity="primary" outlined text raised @click="editDialog = true" />
              <Button icon="pi pi-file-edit" :label="$t(tPathMain + 'remove')" severity="danger" outlined text raised @click="removeConfirm" ></Button>
            </ButtonGroup>
          </template>
      </Card>
      </BlockUI>
    </div>
    <AuthUserEditModal
        :readonlyState="readonlyState"
        :visible="editDialog"
        @visibleChanged="editDialog = $event"
        @readonlyStateChanged="onReadonlyStateChanged"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import AuthUserShowView from "@/views/Protected/auth/user/AuthUserShowView.vue";
import SearchPage from "@/components/Search/SearchPage/SearchPage.vue";
import {AuthUserEntity} from "@/api/auth/user/AuthUserEntity";
import {useAuthUserStore} from "@/stores/auth/authUserStore";
import AuthUserReadRequest from "@/api/auth/user/methods/read/AuthUserReadRequest";
import Violations from "@/api/common/Violations";
import PageLoader from "@/components/Common/PageLoader/PageLoader.vue";
import AuthUserEditModal from "@/modal/Protected/auth/user/AuthUserEditModal.vue";
import {AuthUserRemovePayload} from "@/api/auth/user/methods/remove/AuthUserRemoveRequest";
import type UserCommonOutputContract from "@/api/auth/user/UserCommonOutputContract";
import {generateTitleForDetailsPage} from "@/service/metaService";
import { useHead } from 'unhead'

const authUserStore = useAuthUserStore()

export default defineComponent({
  components: {AuthUserEditModal, PageLoader, SearchPage, AuthUserShowView},

  created() {
    this.onLoadReadonlyState()
  },

  watch: {
    readonlyState() {
      useHead({
        title: generateTitleForDetailsPage(
            this.$t('entities.auth.label'),
            this.$t('entities.auth.entities.user.one'),
            this.readonlyState.id
        )
      })
    }
  },

  data() {
    return {
      tPathMain: 'main.',
      tPathEntity: 'entities.auth.entities.user.',
      locked: false,
      editDialog: false,
      readonlyState: new AuthUserEntity(),
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
            command: () => {
              this.$router.push({ name: 'Auth_User_Search' });
            },
          },
          {
            label: this.$t('main.details'),
          },
        ]
      },
    }
  },

  computed: {
    userId(): string {
      // @ts-ignore
      return this.$route.params.id;
    },
    modelInit(): boolean {
      return this.readonlyState.isInit()
    },
  },

  methods: {
    removeConfirm() {
      this.$confirm.require({
        message: this.$t(this.tPathMain + 'remove_confirm'),
        header: this.$t(this.tPathMain + 'danger_zone'),
        icon: 'pi pi-info-circle',
        rejectLabel: this.$t(this.tPathMain + 'cancel'),
        acceptLabel: this.$t(this.tPathMain + 'remove'),
        rejectClass: 'p-button-secondary p-button-outlined',
        acceptClass: 'p-button-danger',
        accept: () => {
          this.locked = true;
          let payload = new AuthUserRemovePayload();
          payload.id = this.userId;
          authUserStore.remove(payload)
            .then((outputContract: any | Violations) => {
              this.$toast.add({ severity: 'info', summary: this.$t(this.tPathMain + 'events.remove_confirmed'), detail: this.$t(this.tPathEntity + 'events.removed'), life: 3000 });
              this.$router.push({name: 'Auth_User_Search'});
              this.locked = false;
            });
        },
        reject: () => {
          this.locked = false;
        }
      });
    },
    onReadonlyStateChanged(state: AuthUserEntity) {
      this.readonlyState = state;
      this.editDialog = false;
    },
    onLoadReadonlyState() {
      authUserStore.read(
          new AuthUserReadRequest(this.userId)
      ).then((outputContract: UserCommonOutputContract | Violations) => {
        if (outputContract instanceof Violations) {
          this.$toast.add({ severity: 'error', summary: this.$t(this.tPathMain + 'common_fail_load_page.summary'), detail: this.$t(this.tPathMain + 'common_fail_load_page.details'), life: 10000 });

          return;
        }

        this.readonlyState = outputContract.data;
      }).catch(error => {
        this.$toast.add({ severity: 'error', summary: this.$t(this.tPathMain + 'common_fail_load_page.summary'), detail: this.$t(this.tPathMain + 'common_fail_load_page.details'), life: 10000 });
      });
    },
  },
});
</script>

<style lang="scss" scoped>
@import "@/assets/smart-grid";

.row-main {
  @include row-flex();
  justify-content: center;

  .show {
    @include col();
    @include size(6);
    @include size-md(8);
    @include size-sm(12);
  }
  .sidebar {
    @include col();
    @include size(6);
    @include size-sm(4);
    @include size-xs(12);
  }
}
</style>