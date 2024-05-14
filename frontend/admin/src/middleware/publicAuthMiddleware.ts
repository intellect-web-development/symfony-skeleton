import type { RouteLocationNormalized, NavigationGuardNext } from "vue-router";
import {createTokenFromLocalstorage, isAuthenticated} from "@/service/token/tokenService";

const publicAuthMiddleware = (
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
) => {
    let token = createTokenFromLocalstorage();

    if (isAuthenticated(token)) {
        next({ name: 'Welcome' });
    } else {
        next();
    }
};

export default publicAuthMiddleware;
