import BaseJWTRequest from "@/api/common/BaseJWTRequest";

export default class UserReadRequest extends BaseJWTRequest {
    public method = 'get'

    constructor(id: any) {
        super(
            `/api/admin/auth/users/${id}`,
        );
    }
}
