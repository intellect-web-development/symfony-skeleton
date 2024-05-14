import { jwtDecode } from "jwt-decode";

export default class AccessToken {
  decode: Decode
  raw: string

  constructor(jwtToken: string) {
    this.decode = jwtDecode(jwtToken);
    this.raw = jwtToken;
  }
}

interface Decode {
  iat: string,
  exp: string,
  id: string,
  username: string,
  roles: Array<string>,
}
