import { default as authentication } from "@/api/auth/token/methods/authentication";
import { default as refresh } from "@/api/auth/token/methods/refresh";
import { default as invalidateRefreshToken } from "@/api/auth/token/methods/invalidateRefreshToken";

export {
  authentication,
  refresh,
  invalidateRefreshToken,
};
