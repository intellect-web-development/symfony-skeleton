import {Token} from "@/api/auth/token/dto/Token";
import localstorage from "@/dictionary/localstorage";
import Violations from "@/api/Violations";

export function isAuthenticated(token: Token|null): boolean {
    if (null === token) {
        return false;
    }

    let now: Date = new Date()
    // @ts-ignore
    let exp: Date = new Date(token?.access.decode.exp * 1000);

    return now < exp;
}

export function createTokenFromLocalstorage(): Token|null {
    let access = localStorage.getItem(localstorage.auth.token.access);
    let refresh = localStorage.getItem(localstorage.auth.token.refresh);

    if (access === null || refresh === null) {
        return null;
    }

    return new Token({
        access: access,
        refresh: refresh
    });
}

export function createTokenFromLocalstorageOrFail(): Token {
    let token = createTokenFromLocalstorage();

    if (null === token) {
        throw new Error('Failed get token');
    }

    return token;
}

export function forgetToken(): void {
    localStorage.removeItem(localstorage.auth.token.access)
    localStorage.removeItem(localstorage.auth.token.refresh)
}

export function saveToken(token: Token): void
{
    localStorage.setItem(localstorage.auth.token.access, token.access.raw)
    localStorage.setItem(localstorage.auth.token.refresh, token.refresh.raw)
    localStorage.setItem(localstorage.auth.token.userId, token.access.decode.id)
    localStorage.setItem(localstorage.auth.token.userName, token.access.decode.username)
}