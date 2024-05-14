import { jwtDecode } from "jwt-decode";

export default class RefreshToken {
  public decode: Decode
  public raw: string

  constructor(jwtToken: string) {
    this.decode = jwtDecode(jwtToken);
    this.raw = jwtToken;
  }
}

interface Decode {
  iat: string,
  exp: string,
  refresh: {
    userId: string
  },
}
