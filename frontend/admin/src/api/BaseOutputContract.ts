export default class BaseOutputContract {
  public status: number;
  public ok: boolean;
  public data: any;
  public messages: object;

  public constructor(
    status: number,
    ok: boolean,
    data: any,
    messages: object,
  ) {
    this.status = status;
    this.ok = ok;
    this.data = data;
    this.messages = messages;
  }
}
