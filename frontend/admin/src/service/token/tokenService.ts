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
    let access = localStorage.getItem(localstorage.authTokenAccess);
    let refresh = localStorage.getItem(localstorage.authTokenRefresh);

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
    localStorage.removeItem(localstorage.authTokenAccess)
    localStorage.removeItem(localstorage.authTokenRefresh)
}

export function saveToken(token: Token): void
{
    localStorage.setItem(localstorage.authTokenAccess, token.access.raw)
    localStorage.setItem(localstorage.authTokenRefresh, token.refresh.raw)
    localStorage.setItem(localstorage.authTokenUserId, token.access.decode.id)
    localStorage.setItem(localstorage.authTokenUserUserName, token.access.decode.username)
}