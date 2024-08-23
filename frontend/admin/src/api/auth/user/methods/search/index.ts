import AuthUserSearchRequest from './AuthUserSearchRequest';
import UserSearchOutputContract from './UserSearchOutputContract';
import requestApi from "@/api/common/requestApi";

export default function(request: AuthUserSearchRequest) {
  return requestApi(request, UserSearchOutputContract)
}
