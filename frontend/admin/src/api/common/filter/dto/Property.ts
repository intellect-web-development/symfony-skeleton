import PropertyFilter from "@/api/common/filter/dto/PropertyFilter";
import DetailsLink from "@/api/common/filter/dto/DetailsLink";

export default class Property {
  constructor(
    public name: string,
    public label: string,
    public filter: PropertyFilter|null,
    public previewFn: Function|null = null,
    public detailsLink: DetailsLink|null = null,
  ) {
  }
}
