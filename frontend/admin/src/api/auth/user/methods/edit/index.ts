import AuthUserEditRequest from './AuthUserEditRequest';
import requestApi from "@/api/common/requestApi";
import UserCommonOutputContract from "../../UserCommonOutputContract";

export default function(request: AuthUserEditRequest) {
  return requestApi(request, UserCommonOutputContract)
}
