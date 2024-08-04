import InvalidateRefreshTokenRequest from './InvalidateRefreshTokenRequest';
import requestApi from "@/api/common/requestApi";
import InvalidateRefreshTokenOutputContract from "@/api/auth/token/methods/invalidateRefreshToken/InvalidateRefreshTokenOutputContract";

export default function(request: InvalidateRefreshTokenRequest) {
  return requestApi(request, InvalidateRefreshTokenOutputContract)
}
