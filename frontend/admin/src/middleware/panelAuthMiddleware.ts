import type { RouteLocationNormalized, NavigationGuardNext } from "vue-router";
import {createTokenFromLocalstorage, isAuthenticated, saveToken, forgetToken} from "@/service/token/tokenService";
import {useAuthStore} from "@/stores/auth";
import {RefreshTokenPayload} from "@/api/auth/token/methods/refresh/RefreshTokenRequest";
import RefreshTokenOutputContract from "@/api/auth/token/methods/refresh/RefreshTokenOutputContract";
import type {Token} from "@/api/auth/token/dto/Token";

const panelAuthMiddleware = (
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
): void => {
    let token: Token|null = createTokenFromLocalstorage();

    if (null === token) {
        next({ name: 'Login' });

        return;
    }

    let now: Date = new Date()
    // @ts-ignore
    let exp: Date = new Date(token?.access.decode.exp * 1000);

    // @ts-ignore
    const diffInMilliseconds = Math.abs(exp - now);
    const diffInMinutes = Math.floor(diffInMilliseconds / (1000 * 60));

    if ((diffInMinutes < 30) || (now > exp)) {
        setTimeout(() => {
            if (null !== createTokenFromLocalstorage()) {
                const authStore = useAuthStore();
                let refreshTokenPayload = new RefreshTokenPayload();
                refreshTokenPayload.refreshToken = token?.refresh?.raw ?? null;

                let violations = refreshTokenPayload.validate();
                if ('{}' !== JSON.stringify(violations.violations)) {
                    console.error('Refresh access token is failed', violations.violations);

                    forgetToken();
                    next({ name: 'login' });

                    return;
                }

                console.log('Refresh token started')
                authStore.refreshToken(refreshTokenPayload)
                    .then((output) => {
                        if (output instanceof RefreshTokenOutputContract) {
                            saveToken(output.data)
                        }

                        return output;
                    });
            }
        }, 5000);
    }

    if (!isAuthenticated(token)) {
        forgetToken();
        next({ name: 'login' });
    } else {
        next();
    }
};

export default panelAuthMiddleware;
