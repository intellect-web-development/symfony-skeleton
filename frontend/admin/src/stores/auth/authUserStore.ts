import { defineStore } from 'pinia';
import {createTokenFromLocalstorageOrFail} from "@/service/token/tokenService";
import Violations from "@/api/common/Violations";
import {api} from "@/api";
import type UserReadRequest from "@/api/auth/user/methods/read/UserReadRequest";
import type UserSearchRequest from "@/api/auth/user/methods/search/UserSearchRequest";
import UserCommonOutputContract from "@/api/auth/user/UserCommonOutputContract";
import UserSearchOutputContract from "@/api/auth/user/methods/search/UserSearchOutputContract";

export const useAuthUserStore = defineStore('auth.user', {
    actions: {
        async read(request: UserReadRequest): Promise<UserCommonOutputContract | Violations> {
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
        async search(request: UserSearchRequest): Promise<UserSearchOutputContract | Violations> {
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
    }
});
