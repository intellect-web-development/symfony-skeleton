import AuthUserReadRequest from './AuthUserReadRequest';
import UserCommonOutputContract from '../../UserCommonOutputContract';
import requestApi from "@/api/common/requestApi";

export default function(request: AuthUserReadRequest) {
  return requestApi(request, UserCommonOutputContract)
}
