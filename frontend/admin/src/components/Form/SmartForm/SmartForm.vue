<template>
  <h1>
    <slot name="header" />
  </h1>
  <div>
    <Message v-if="hasViolationsOutOfPayload" severity="warn" style="margin-bottom: 0.5rem">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <p>{{ $t('components.smart-form.violations-out-of-payload') }}</p>
        <Button
          :badge="violationsOutOfPayloadCount"
          badgeSeverity="warning"
          type="button"
          label="Показать"
          severity="secondary"
          @click="toggleOutOfFormViolations"
        />
      </div>
    </Message>
    <OverlayPanel ref="outOfFormViolations">
      <template v-if="violationsOutOfPayload">
        <p class="violation" v-for="(message, field) in violationsOutOfPayload">{{field}}: {{message}}</p>
      </template>
    </OverlayPanel>

    <template v-for="(input) in sortedInputs">
      <BlockUI :blocked="formLocked">
        <div class="p-inputgroup mt-1">
          <span class="p-inputgroup-addon form-label">{{ input.label }}</span>
          <InputText
              v-if="input.type === InputType.Text"
              @keydown="submitFormOnPressEnter"
              class="form-input"
              type="text"
              @change="onValidate"
              v-model="
              //@ts-ignore
              payload[input.name]
            "
              :invalid="
              //@ts-ignore
              invalidFieldsMap[input.name]
            "
          />

          <Textarea
              v-if="input.type === InputType.TextArea"
              @keydown="submitFormOnPressEnter"
              class="form-input"
              type="text"
              rows="5"
              @change="onValidate"
              v-model="
              //@ts-ignore
              payload[input.name]
            "
              :invalid="
              //@ts-ignore
              invalidFieldsMap[input.name]
            "
          />
        </div>
        <p class="violation" v-if="
          //@ts-ignore
          invalidFieldsMap[input.name]
        ">
          {{ //@ts-ignore
        violationsMap[input.name]
          }}
        </p>
        <p v-else style="margin-top: 1rem"></p>
      </BlockUI>
    </template>

    <div style="display: flex; align-items: center; justify-content: end;">
      <Button :label="submitLabel" @click="onSubmit" :disabled="submitLocked" :loading="formLocked" />
    </div>
  </div>
  <Toast position="bottom-right" />
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import Violations from "@/api/common/Violations";
import InputSchema, {InputType} from "@/components/Form/SmartForm/InputSchema";
import FormSchema from "@/components/Form/SmartForm/FormSchema";
import debounce from 'debounce';

export default defineComponent({
  props: {
    submitLabel: {
      type: String,
      default: '',
    },
    payload: {
      type: Object,
      default: '',
    },
    schema: {
      type: FormSchema,
      default: '',
    },
    onSubmitCallback: {
      type: Function,
      required: true,
    }
  },
  data() {
    return {
      tPath: 'components.form.smart_form.',
      violations: new Violations(),
      formLocked: false,
      formIsUntouched: true,
      payloadSnapshot: Object,
    }
  },
  computed: {
    InputType() {
      return InputType
    },
    submitLocked(): boolean {
      return this.hasViolations || this.formIsUntouched;
    },
    hasViolationsOutOfPayload(): boolean {
      return JSON.stringify(this.violationsOutOfPayload) !== '{}';
    },
    violationsOutOfPayloadCount(): string {
      return Object.values(this.violationsOutOfPayload).length.toString();
    },
    hasViolations(): boolean {
      return JSON.stringify(this.violationsMap) !== '{}';
    },
    sortedInputs(): InputSchema[] {
      return Object.values(this.schema.inputs).sort((a, b) => a.order - b.order);
    },
    invalidFieldsMap() {
      let map = {};
      for (let name in this.violationsMap) {
        // @ts-ignore
        map[name] = null !== this.payloadSnapshot[name] && this.fieldIsInvalid(name);
      }

      return map
    },
    violationsMap() {
      let violations = {};
      for (let fieldName in this.violations.violations) {
        // @ts-ignore
        let message = this.violations.violations[fieldName];
        message = message.charAt(0).toUpperCase() + message.slice(1);

        // @ts-ignore
        violations[fieldName] = message;
      }

      return violations;
    },
    violationsOutOfPayload() {
      let violations = {};
      for (let fieldName in this.violationsMap) {
        if (this.schema.inputs.hasOwnProperty(fieldName)) {
          continue;
        }

        // @ts-ignore
        violations[fieldName] = this.violationsMap[fieldName];
      }

      return violations;
    }
  },
  created() {
    this.onValidate = debounce(this.onValidate, 300);
    this.onValidateWithTimeout = debounce(this.onValidate, 800);

    // Если есть хотя бы одно предзаполненное поле, то вызываем валидацию
    for (let value of Object.values(this.payload)) {
      if (!!value) {
        this.onValidate();
        break;
      }
    }
  },
  methods: {
    onValidate() {
      if (this.formIsUntouched) {
        this.formIsUntouched = false;
      }

      this.violations = this.payload.validate();

      // @ts-ignore
      this.payloadSnapshot = { ...this.payload };
    },
    onValidateWithTimeout() {
      // callback for call onValidate with late
    },
    fieldIsInvalid(field: string) {
      return this.violationsMap.hasOwnProperty(field);
    },
    toggleOutOfFormViolations(event: Event) {
      // @ts-ignore
      this.$refs.outOfFormViolations.toggle(event);
    },
    submitFormOnPressEnter(event: KeyboardEvent) {
      // Эту штуку можно вешать только на input. На textarea - нельзя, так как там enter это просто перенос строки
      if (event.key === "Enter") {
        this.onSubmit();
      }
    },
    async onSubmit() {
      if (this.submitLocked) {
        return;
      }
      this.formLocked = true;

      let result = await this.onSubmitCallback();

      if (!result) {
        return;
      }

      if (result instanceof Violations) {
        let violations = {};
        for (let i in result.violations) {
          if (!isNaN(parseFloat(i))) {
            // @ts-ignore
            let message = result.violations[i];
            this.$toast.add({
              severity: 'warn',
              summary: this.$t('main.error'),
              detail: message,
              life: 10000
            }
            );
          } else {
            // @ts-ignore
            violations[i] = result.violations[i];
          }
        }
        this.violations = new Violations(violations);
      } else {
        if (result.status >= 200 && result.status < 300) {
          this.$toast.add({
            severity: 'success',
            summary: this.$t('main.success'),
            detail: Object.values(result.messages)[0],
            life: 10000
          }
          );
        }
      }

      this.formLocked = false;

      return result;
    }
  },
  watch: {
    payload: {
      handler() {
        this.onValidate();
      },
      deep: true
    }
  }
})
</script>

<style lang="scss" scoped>
.violation {
  color: var(--red-400);
  margin: 0;
}
.form-label {
  width: 25%;
  justify-content: center;
  text-align: center;
}
.form-input {
  flex-grow: 1;
}
</style>