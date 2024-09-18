<template>
  <h1>
    <slot name="header" />
  </h1>
  <div>
    <Message v-if="hasViolationsOutOfPayload" severity="warn" style="margin-bottom: 0.5rem">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <p>{{ $t(tPath + 'violations_out_of_payload') }}</p>
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
              v-if="input instanceof InputSchema"
              @keydown="submitFormOnPressEnter"
              class="form-input"
              type="text"
              @change="onChangePayload(input)"
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
              v-if="input instanceof TextAreaInputSchema"
              @keydown="submitFormOnPressEnter"
              class="form-input"
              type="text"
              rows="5"
              @change="onChangePayload(input)"
              v-model="
              //@ts-ignore
              payload[input.name]
            "
              :invalid="
              //@ts-ignore
              invalidFieldsMap[input.name]
            "
          />

          <Password
              v-if="input instanceof NewPasswordInputSchema"
              @input="onChangePayload(input)"
              @keydown="submitFormOnPressEnter"
              class="form-input"
              :promptLabel="$t(tPath + ('password_input.prompt_label'))"
              :weakLabel="$t(tPath + ('password_input.weak_label'))"
              :mediumLabel="$t(tPath + ('password_input.medium_label'))"
              :strongLabel="$t(tPath + ('password_input.strong_label'))"
              toggleMask
              feedback
              v-model="
              //@ts-ignore
              payload[input.name]
            "
              :invalid="
              //@ts-ignore
              invalidFieldsMap[input.name]
            "
          />


          <Password
              v-if="input instanceof CheckPasswordInputSchema"
              @keydown="submitFormOnPressEnter"
              @input="onChangePayload(input)"
              class="form-input"
              :promptLabel="$t(tPath + ('password_input.prompt_label'))"
              :weakLabel="$t(tPath + ('password_input.weak_label'))"
              :mediumLabel="$t(tPath + ('password_input.medium_label'))"
              :strongLabel="$t(tPath + ('password_input.strong_label'))"
              toggleMask
              :feedback="false"
              v-model="
              //@ts-ignore
              payload[input.name]
            "
              :invalid="
              //@ts-ignore
              invalidFieldsMap[input.name]
            "
          />

          <Dropdown
              v-if="input instanceof ChoiceInputSchema"
              @change="onChangePayload(input)"
              v-model="
              //@ts-ignore
              tempPayload[input.name]"
              optionLabel="label"
              @update:model-value="
              //@ts-ignore
              payload[input.name] = tempPayload[input.name].value;"
              :options="input.cases"
              :editable="input.editable"
          />

          <SearchInput
              v-if="input instanceof SearchInputSchema"
              :searchProperties="input.searchProperties"
              :targetValue="input.targetValue"
              :searchMethod="input.searchMethod"
              :searchRequest="input.searchRequest"
              :propertyLabelFn="input.propertyLabelFn"
              @value-updated="payload[input.name] = $event"
              :payload="payload[input.name]"
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

    <div style="display: flex; align-items: center; justify-content: end; margin-top: 1rem">
      <Button :label="submitLabel" @click="onSubmit" :disabled="submitLocked" :loading="formLocked" />
    </div>
  </div>
  <!-- todo <IWD-3321>: отверстать этот компонент по БЭМ -->
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import Violations from "@/api/common/Violations";
import AbstractInputSchema from "@/components/Form/SmartForm/InputSchema/AbstractInputSchema";
import FormSchema from "@/components/Form/SmartForm/FormSchema";
import debounce from 'debounce';
import TextInputSchema from "@/components/Form/SmartForm/InputSchema/TextInputSchema";
import TextAreaInputSchema from "@/components/Form/SmartForm/InputSchema/TextAreaInputSchema";
import NewPasswordInputSchema from "@/components/Form/SmartForm/InputSchema/NewPasswordInputSchema";
import CheckPasswordInputSchema from "@/components/Form/SmartForm/InputSchema/CheckPasswordInputSchema";
import SearchInput from "@/components/Form/Input/SearchInput/SearchInput.vue";
import SearchInputSchema from "@/components/Form/SmartForm/InputSchema/SearchInputSchema";
import ChoiceInputSchema from "@/components/Form/SmartForm/InputSchema/ChoiceInputSchema";

export default defineComponent({
  components: {SearchInput},
  emits: [ "successSubmit" ],
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
    },
    validateTimeout: {
      type: Number,
      default: 300
    },
  },
  data() {
    return {
      tPath: 'components.form.smart_form.',
      tPathMain: 'main.',
      violations: new Violations(),
      formLocked: false,
      formIsUntouched: true,
      payloadSnapshot: Object,
      tempPayload: {},
      touchedInputs: {},
    }
  },
  computed: {
    ChoiceInputSchema() {
      return ChoiceInputSchema
    },
    SearchInputSchema() {
      return SearchInputSchema
    },
    CheckPasswordInputSchema() {
      return CheckPasswordInputSchema
    },
    NewPasswordInputSchema() {
      return NewPasswordInputSchema
    },
    TextAreaInputSchema() {
      return TextAreaInputSchema
    },
    InputSchema() {
      return TextInputSchema
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
    sortedInputs(): AbstractInputSchema[] {
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
    this.onValidate = debounce(this.onValidate, this.validateTimeout);

    // Если есть хотя бы одно предзаполненное поле, то вызываем валидацию
    for (let value of Object.values(this.payload)) {
      if (!!value) {
        this.onValidate();
        break;
      }
    }

    // Предзаполняем temp-значения ChoiceInputSchema
    for (let propertyName in this.payload) {
      let propertyValue = this.payload[propertyName];
      //@ts-ignore
      if (this.schema.inputs.hasOwnProperty(propertyName) && this.schema.inputs[propertyName] instanceof ChoiceInputSchema) {
        //@ts-ignore
        this.tempPayload[propertyName] = this.schema.inputs[propertyName].cases.find((element: { value: string }) => element.value === propertyValue);
      }
    }
  },
  methods: {
    onChangePayload(input: AbstractInputSchema|null = null) {
      if (input) {
        //@ts-ignore
        this.touchedInputs[input.name] = input;
      }
      if (this.formIsUntouched) {
        this.formIsUntouched = false;
      }
    },
    onValidate() {
      const prototype = Object.getPrototypeOf(this.payload);

      if (typeof prototype.constructor === 'function') {
        this.payloadSnapshot = new prototype.constructor();

        for (let name in this.touchedInputs) {
          //@ts-ignore
          this.payloadSnapshot[name] = this.payload[name];
        }
      }

      //@ts-ignore
      this.violations = this.payloadSnapshot.validate();
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
                  summary: this.$t(this.tPathMain + 'error'),
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
          this.$emit('successSubmit', result)
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
  margin: 0.5rem;
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