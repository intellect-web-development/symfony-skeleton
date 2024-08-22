<template>
  <div class="container container-center">
    <div class="row">
      <div class="col">
        <SmartForm
            :submit-label="$t(tPath + 'form.submit')"
            :payload="form.payload"
            :schema="form.schema"
            :on-submit-callback="onSubmit"
        >
          <template #header>
            {{ $t(tPath + 'form.header') }}
          </template>
        </SmartForm>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import SmartForm from "@/components/Form/SmartForm/SmartForm.vue";
import {AuthenticationPayload} from "@/api/auth/token/methods/authentication/AuthenticationRequest";
import FormSchema from "@/components/Form/SmartForm/FormSchema";
import {useAuthStore} from "@/stores/auth/authTokenStore";
import AuthenticationOutputContract from "@/api/auth/token/methods/authentication/AuthenticationOutputContract";
import {saveToken} from "@/service/token/tokenService";
import TextInputSchema from "@/components/Form/SmartForm/InputSchema/TextInputSchema";
import CheckPasswordInputSchema from "@/components/Form/SmartForm/InputSchema/CheckPasswordInputSchema";
import {useHead} from "unhead";
import {projectName} from "@/router/routerDictionary";

const authStore = useAuthStore();
export default {
  components: {SmartForm},

  created() {
    useHead({
      title: this.$t(this.tPath + 'title') + ' | ' + projectName,
    })

    this.form.schema.add(
        new TextInputSchema(
            'email',
            this.$t(this.tPath + 'form.input.email'),
        )
    )
    this.form.schema.add(
        new CheckPasswordInputSchema(
            'password',
            this.$t(this.tPath + 'form.input.password'),
        )
    )
  },

  data() {
    return {
      tPath: 'pages.public.login.',
      form: {
        payload: new AuthenticationPayload(),
        schema: new FormSchema(),
      }
    }
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

            this.$router.push({name: 'ProtectedWelcome'})

            return output;
        });
    }
  }
}
</script>

<style lang="scss" scoped>
@import "@/assets/smart-grid";

.row {
  @include row-flex();
  justify-content: center;

  .col {
    @include col();
    @include size(6);
    @include size-sm(10);
    @include size-xs(12);
  }
}
</style>
