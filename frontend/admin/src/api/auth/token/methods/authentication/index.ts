import AuthenticationRequest from './AuthenticationRequest';
import requestApi from "@/api/requestApi";
import AuthenticationOutputContract from "@/api/auth/token/methods/authentication/AuthenticationOutputContract";

export default function(request: AuthenticationRequest) {
  return requestApi(request, AuthenticationOutputContract)
}
