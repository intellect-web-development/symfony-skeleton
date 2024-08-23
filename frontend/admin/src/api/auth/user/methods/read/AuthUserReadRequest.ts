import BaseJWTRequest from "@/api/common/BaseJWTRequest";

export default class AuthUserReadRequest extends BaseJWTRequest {
  public method = 'get'

  constructor(id: any) {
    super(
      `/api/admin/auth/users/${id}`,
    );
  }
}
