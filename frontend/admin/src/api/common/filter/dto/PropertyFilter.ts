import type {Maybe} from "yup";
import type SearchMode from "@/api/common/filter/constants/SearchMode";
import type {CaseType} from "@/components/Common/CaseType/CaseType";

declare interface Opts {
  type: SearchMode
  /**
   * Example: 'domainModel.id', для того, чтобы на бэке производить фильтрацию по отличному относительному пути.
   * Если базовое название поля domainModelId, а в DQL domainModel.id, то можем это указать, и оно начнет работать
   * (но это еще скорее всего надо будет реализовать).
   */
  path?: string|null
  states?: CaseType[]
  editable?: boolean|null
  validation?: Maybe<any>
}

// todo <IWD-3320>: в скором времени этот файл превратится в неподдерживаемую помойку, необходимо применить полиморфизм, по аналогии со SmartForm
export default class PropertyFilter {
  public type: SearchMode;
  public path: string|null;
  public states?: CaseType[]|null;
  public editable?: boolean|null;
  public validation?: Maybe<any>;

  constructor(opts: Opts) {
    this.type = opts.type;
    this.states = opts.states ?? null;
    this.path = opts.path ?? null;
    this.editable = opts.editable ?? null;
    this.validation = opts.validation
  }
}
