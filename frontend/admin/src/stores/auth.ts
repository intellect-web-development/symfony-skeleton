import {defineStore} from 'pinia';
import type BaseOutputContract from "@/api/BaseOutputContract";
import {api} from "@/api";
import Violations from "@/api/Violations";
import type {AuthenticationPayload} from "@/api/auth/token/methods/authentication/AuthenticationRequest";
import AuthenticationRequest from "@/api/auth/token/methods/authentication/AuthenticationRequest";
import type {RefreshTokenPayload} from "@/api/auth/token/methods/refresh/RefreshTokenRequest";
import RefreshTokenRequest from "@/api/auth/token/methods/refresh/RefreshTokenRequest";
import InvalidateRefreshTokenRequest, {
  InvalidateRefreshTokenPayload
} from "@/api/auth/token/methods/invalidateRefreshToken/InvalidateRefreshTokenRequest";
import { createTokenFromLocalstorageOrFail } from "@/service/token/tokenService";

export const useAuthStore = defineStore('auth.user', {
  getters: {
    userEmail(state): string
    {
      let token = createTokenFromLocalstorageOrFail();

      return token.access.decode.username;
    },
  },
  actions: {
    async authentication(payload: AuthenticationPayload): Promise<BaseOutputContract | Violations> {
      try {
        return await api.auth.authentication(new AuthenticationRequest(payload));
      } catch(err) {
        //@ts-ignore
        let messages = err.response.data.messages;
        return new Violations(messages)
      }
    },
    async refreshToken(payload: RefreshTokenPayload): Promise<BaseOutputContract | Violations> {
      try {
        return await api.auth.refresh(new RefreshTokenRequest(payload));
      } catch(err) {
        //@ts-ignore
        let messages = err.response.data.messages;
        return new Violations(messages)
      }
    },
    async invalidateRefreshToken(): Promise<BaseOutputContract | Violations> {
      try {
        let payload = new InvalidateRefreshTokenPayload();
        let token = createTokenFromLocalstorageOrFail();
        payload.refreshToken = token.refresh.raw;

        return await api.auth.invalidateRefreshToken(new InvalidateRefreshTokenRequest(payload, token?.access));
      } catch(err) {
        console.log(err)
        //@ts-ignore
        let messages = err.response.data.messages;
        return new Violations(messages)
      }
    },
  }
});
