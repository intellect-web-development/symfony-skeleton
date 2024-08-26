<template>
  <SmartForm
      :submitLabel="$t(tPathMain + 'edit')"
      :payload="form.payload"
      :schema="form.schema"
      :onSubmitCallback="onSubmit"
      @successSubmit="onSuccessSubmit"
  >
    <template #header>
    </template>
  </SmartForm>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import SmartForm from "@/components/Form/SmartForm/SmartForm.vue";
import {AuthUserEditPayload} from "@/api/auth/user/methods/edit/AuthUserEditRequest";
import FormSchema from "@/components/Form/SmartForm/FormSchema";
import UserCommonOutputContract from "@/api/auth/user/UserCommonOutputContract";
import {useAuthUserStore} from "@/stores/auth/authUserStore";
import TextInputSchema from "@/components/Form/SmartForm/InputSchema/TextInputSchema";
import ChoiceInputSchema from "@/components/Form/SmartForm/InputSchema/ChoiceInputSchema";
import type {CaseType} from "@/components/Common/CaseType/CaseType";
import {dataMap} from "@/service/mutations";
import {AuthUserEntity} from "@/api/auth/user/AuthUserEntity";


const authUserStore = useAuthUserStore();

export default defineComponent({
  components: {SmartForm},
  emits: [ "readonlyStateChanged" ],
  props: {
    readonlyState: {
      type: AuthUserEntity,
      required: true,
    }
  },
  data() {
    return {
      tPathMain: 'main.',
      tPathEntity: 'entities.auth.entities.user.',
      form: {
        payload: new AuthUserEditPayload(),
        schema: new FormSchema(),
      }
    }
  },
  computed: {

  },
  created() {
    dataMap(this.form.payload, this.readonlyState);

    this.form.schema.add(
      new TextInputSchema(
        'email',
        this.$t(this.tPathEntity + 'properties.email'),
      ),
    )
  },
  methods: {
    async onSubmit() {
      return await authUserStore.edit(this.form.payload)
          .then((outputContract) => {
            if (outputContract instanceof UserCommonOutputContract) {
              this.$emit('readonlyStateChanged', outputContract.data)
              dataMap(this.form.payload, outputContract.data);
            }

            return outputContract;
          });
    },
    onSuccessSubmit(outputContract: UserCommonOutputContract) {
      this.$toast.add({
          severity: 'success',
          summary: this.$t(this.tPathEntity + 'events.edited'),
          detail: Object.values(outputContract.messages)[0],
          life: 10000
        }
      );
    },
  },
});
</script>
