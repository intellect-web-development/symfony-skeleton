import RefreshTokenRequest from './RefreshTokenRequest';
import requestApi from "@/api/common/requestApi";
import RefreshTokenOutputContract from "@/api/auth/token/methods/refresh/RefreshTokenOutputContract";

export default function(request: RefreshTokenRequest) {
  return requestApi(request, RefreshTokenOutputContract)
}
