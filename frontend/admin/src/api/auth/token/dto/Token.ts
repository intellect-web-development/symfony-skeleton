import AccessToken from "@/api/auth/token/dto/AccessToken";
import RefreshToken from "@/api/auth/token/dto/RefreshToken";

export interface TokenInterface {
  access: string;
  refresh: string;
}

export class Token {
  public access: AccessToken;
  public refresh: RefreshToken;

  public constructor(
    data: TokenInterface
  ) {
    this.access = new AccessToken(data.access);
    this.refresh = new RefreshToken(data.refresh);
  }
}