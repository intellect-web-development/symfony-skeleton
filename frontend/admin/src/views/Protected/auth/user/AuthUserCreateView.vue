<template>
  <SmartForm
      :submitLabel="$t(tPathMain + 'create')"
      :payload="form.payload"
      :schema="form.schema"
      :onSubmitCallback="onSubmit"
      @successSubmit="onSuccessSubmit"
  >
    <template #header>
    </template>
  </SmartForm>
  <EntityCreatedToast />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import SmartForm from "@/components/Form/SmartForm/SmartForm.vue";
import {AuthUserCreatePayload} from "@/api/auth/user/methods/create/AuthUserCreateRequest";
import FormSchema from "@/components/Form/SmartForm/FormSchema";
import UserCommonOutputContract from "@/api/auth/user/UserCommonOutputContract";
import {useAuthUserStore} from "@/stores/auth/authUserStore";
import TextInputSchema from "@/components/Form/SmartForm/InputSchema/TextInputSchema";
import ChoiceInputSchema from "@/components/Form/SmartForm/InputSchema/ChoiceInputSchema";
import type {CaseType} from "@/components/Common/CaseType/CaseType";
import {dataMap} from "@/service/mutations";
import EntityCreatedToast from "@/components/Common/Toast/EntityCreated/EntityCreatedToast.vue";


const authUserStore = useAuthUserStore();

export default defineComponent({
  components: {EntityCreatedToast, SmartForm},
  emits: [ "entityCreated" ],
  props: {
    fallbackFormPayload: {
      type: Object,
      default: new AuthUserCreatePayload(),
    }
  },

  data() {
    return {
      tPathMain: 'main.',
      tPathEntity: 'entities.auth.entities.user.',
      form: {
        payload: new AuthUserCreatePayload(),
        schema: new FormSchema(),
      }
    }
  },
  computed: {

  },

  created() {
    dataMap(this.form.payload, this.fallbackFormPayload);

    this.form.schema.add(
      new TextInputSchema(
        'email',
        this.$t(this.tPathEntity + 'properties.email'),
      ),
    )
  },
  methods: {
    async onSubmit() {
      return await authUserStore.create(this.form.payload)
          .then((outputContract) => {
            if (outputContract instanceof UserCommonOutputContract) {
              this.$emit('entityCreated', outputContract.data)
              dataMap(this.form.payload, this.fallbackFormPayload);
            }

            return outputContract;
          });
    },
    onSuccessSubmit(outputContract: UserCommonOutputContract) {
      this.$toast.add({
        severity: 'success',
        summary: this.$t(this.tPathEntity + 'events.created'),
        detail: Object.values(outputContract.messages)[0],
        life: 10000,
        group: 'entity_created',
        //@ts-ignore
        to: {name: 'Auth_User_Details', params: {id: outputContract.data.id}},
      });
    },
  },
});
</script>
