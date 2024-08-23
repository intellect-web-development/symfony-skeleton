import { defineStore } from 'pinia';
import {createTokenFromLocalstorageOrFail} from "@/service/token/tokenService";
import Violations from "@/api/common/Violations";
import {api} from "@/api";
import type AuthUserReadRequest from "@/api/auth/user/methods/read/AuthUserReadRequest";
import type AuthUserSearchRequest from "@/api/auth/user/methods/search/AuthUserSearchRequest";
import AuthUserCreateRequest, {AuthUserCreatePayload} from "@/api/auth/user/methods/create/AuthUserCreateRequest";
import AuthUserEditRequest, {AuthUserEditPayload} from "@/api/auth/user/methods/edit/AuthUserEditRequest";
import AuthUserRemoveRequest, {AuthUserRemovePayload} from "@/api/auth/user/methods/remove/AuthUserRemoveRequest";
import UserCommonOutputContract from "@/api/auth/user/UserCommonOutputContract";
import UserSearchOutputContract from "@/api/auth/user/methods/search/UserSearchOutputContract";

export const useAuthUserStore = defineStore('auth.user', {
  actions: {
    async read(request: AuthUserReadRequest): Promise<UserCommonOutputContract | Violations> {
      try {
        let token = createTokenFromLocalstorageOrFail();
        request.setAccessToken(token.access.raw)

        return await api.auth.user.read(request);
      } catch(err) {
        //@ts-ignore
        let messages = err.response.data.messages;
        return new Violations(messages)
      }
    },
    async search(request: AuthUserSearchRequest): Promise<UserSearchOutputContract | Violations> {
      try {
        let token = createTokenFromLocalstorageOrFail();
        request.setAccessToken(token.access.raw)

        return await api.auth.user.search(request);
      } catch(err) {
        //@ts-ignore
        let messages = err.response.data.messages;
        return new Violations(messages)
      }
    },
    async create(payload: AuthUserCreatePayload): Promise<UserCommonOutputContract | Violations> {
      try {
        let token = createTokenFromLocalstorageOrFail();

        let violations = payload.validate();
        if (!violations.isEmpty()) {
          return violations;
        }
        let result = await api.auth.user.create(
          new AuthUserCreateRequest(payload, token?.access)
        );

        return result;
      } catch(err) {
        //@ts-ignore
        let messages = err.response.data.messages;
        return new Violations(messages)
      }
    },
    async edit(payload: AuthUserEditPayload): Promise<UserCommonOutputContract | Violations> {
      try {
        let token = createTokenFromLocalstorageOrFail();

        let violations = payload.validate();
        if (!violations.isEmpty()) {
          return violations;
        }
        let result = await api.auth.user.edit(
          new AuthUserEditRequest(payload, token?.access)
        );

        return result;
      } catch(err) {
        //@ts-ignore
        let messages = err.response.data.messages;
        return new Violations(messages)
      }
    },
    async remove(payload: AuthUserRemovePayload): Promise<UserCommonOutputContract | Violations> {
      try {
        let token = createTokenFromLocalstorageOrFail();

        let violations = payload.validate();
        if (!violations.isEmpty()) {
          return violations;
        }
        let result = await api.auth.user.remove(
          new AuthUserRemoveRequest(payload, token?.access)
        );

        return result;
      } catch(err) {
        //@ts-ignore
        let messages = err.response.data.messages;
        return new Violations(messages)
      }
    },
  }
});
