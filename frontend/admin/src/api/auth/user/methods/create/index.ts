import AuthUserCreateRequest from './AuthUserCreateRequest';
import requestApi from "@/api/common/requestApi";
import UserCommonOutputContract from "../../UserCommonOutputContract";

export default function(request: AuthUserCreateRequest) {
  return requestApi(request, UserCommonOutputContract)
}
