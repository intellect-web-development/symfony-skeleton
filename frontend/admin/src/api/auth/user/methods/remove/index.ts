import AuthUserRemoveRequest from './AuthUserRemoveRequest';
import requestApi from "@/api/common/requestApi";
import UserCommonOutputContract from "../../UserCommonOutputContract";

export default function(request: AuthUserRemoveRequest) {
  return requestApi(request, UserCommonOutputContract)
}
