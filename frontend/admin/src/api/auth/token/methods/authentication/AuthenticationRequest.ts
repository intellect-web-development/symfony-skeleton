import BaseRequest from "@/api/BaseRequest";
import Violations from "@/api/Violations";
import * as yup from 'yup';
import { email } from "@/translations/ru/yup-translation";
import {validate} from "@/api/validator";
import type FormPayloadInterface from "@/api/FormPayloadInterface";

export class AuthenticationPayload implements FormPayloadInterface {
  email: string|null = null;
  password: string|null = null;
  // passwordRepeat: string|null = null;

  static schema = yup.object({
    email: yup.string()
        .required()
        .email(email),
    password: yup.string()
        .required(),
        // .min(6),
    // passwordRepeat: yup.string()
    //     .required()
    //     .oneOf([yup.ref('password'), 'null'], 'passwordRepeat Пароли должны совпадать') // todo: to translator
    //     .min(6)
    // todo: это пока останется тут как example, нужно будет там, где пароль надо будет повторять.
  });

  public validate(): Violations
  {
    return validate(AuthenticationPayload.schema, this);
  }
}

export default class AuthenticationRequest extends BaseRequest {
  public method = 'post'

  public data = new AuthenticationPayload()

  constructor(payload: AuthenticationPayload|null) {
    super(`/api/token/authentication`);
    if (payload) {
      this.data = payload;
    }
  }
}
