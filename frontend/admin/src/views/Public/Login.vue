<template>
  <div class="container container-center">
    <div class="row">
      <div class="col">
        <SmartForm
            :submit-label="$t('views.login.form.submit')"
            :payload="form.payload"
            :schema="form.schema"
            :on-submit-callback="onSubmit"
        >
          <template #header>
            {{ $t('views.login.form.submit') }}
          </template>
        </SmartForm>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import SmartForm from "@/components/SmartForm/SmartForm.vue";
import {AuthenticationPayload} from "@/api/auth/token/methods/authentication/AuthenticationRequest";
import FormSchema from "@/components/SmartForm/FormSchema";
import InputSchema, {InputType} from "@/components/SmartForm/InputSchema";
import {useAuthStore} from "@/stores/auth";
import AuthenticationOutputContract from "@/api/auth/token/methods/authentication/AuthenticationOutputContract";
import {saveToken} from "@/service/token/tokenService";

const authStore = useAuthStore();

export default {
  components: {SmartForm},
  data() {
    return {
      form: {
        payload: new AuthenticationPayload(),
        schema: new FormSchema(),
      }
    }
  },
  created() {
    this.form.schema.add(
        new InputSchema(
            'email',
            this.$t('views.login.form.input.email'),
            InputType.Text,
        )
    )
    this.form.schema.add(
        new InputSchema(
            'password',
            this.$t('views.login.form.input.password'),
            InputType.Text,
        )
    )
  },
  methods: {
    async onSubmit() {
      return await authStore.authentication(this.form.payload)
          .then((output) => {
            if (output instanceof AuthenticationOutputContract) {
              saveToken(output.data)
            }

            this.form.payload.email = null;
            this.form.payload.password = null;

            this.$router.push({name: 'Welcome'})

            return output;
        });
    }
  }
}
</script>

<style lang="scss" scoped>
@import "smart-grid/smart-grid";

.row {
  @include row-flex();
  justify-content: center;

  .col {
    @include col();
    @include size(8);
    @include size-sm(10);
    @include size-xs(12);
  }
}
</style>
