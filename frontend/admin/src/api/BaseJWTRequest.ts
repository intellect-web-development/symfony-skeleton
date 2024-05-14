import BaseRequest from "@/api/BaseRequest";

export default class BaseJWTRequest extends BaseRequest {
  public headers = {
    Authorization: ''
  }

  public setAccessToken(accessToken: string) {
    this.headers.Authorization = 'Bearer ' + accessToken
  }
}
