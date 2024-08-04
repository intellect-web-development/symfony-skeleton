import type { AxiosRequestConfig } from 'axios';

export default class BaseRequest implements AxiosRequestConfig {
  public constructor(
    public url: string
  ) {
  }
}
