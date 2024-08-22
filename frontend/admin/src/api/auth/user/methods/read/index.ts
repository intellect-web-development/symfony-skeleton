import UserReadRequest from './UserReadRequest';
import UserCommonOutputContract from '../../UserCommonOutputContract';
import requestApi from "@/api/common/requestApi";

export default function(request: UserReadRequest) {
    return requestApi(request, UserCommonOutputContract)
}
