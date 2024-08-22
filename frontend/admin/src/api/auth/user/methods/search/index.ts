import UserSearchRequest from './UserSearchRequest';
import UserSearchOutputContract from './UserSearchOutputContract';
import requestApi from "@/api/common/requestApi";

export default function(request: UserSearchRequest) {
    return requestApi(request, UserSearchOutputContract)
}
