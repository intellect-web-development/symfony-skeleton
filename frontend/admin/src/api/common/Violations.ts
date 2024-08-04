export default class Violations {
  public constructor(
    public violations: object = {}
  ) {
  }

  public isEmpty(): boolean {
    return JSON.stringify(this.violations) === '{}'
  }
}
