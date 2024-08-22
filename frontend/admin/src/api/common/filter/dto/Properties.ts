import Property from "@/api/common/filter/dto/Property";
import type {ObjectSchema} from "yup";
import * as yup from "yup";

export default class Properties {
  public validateSchema: ObjectSchema<any>;

  constructor(
    public properties: Property[],
  ) {
    let spec = {};
    for (let i in this.properties) {
      let property = this.properties[i];
      //@ts-ignore
      spec[property.name] = property.filter?.validation;
    }

    this.validateSchema = yup.object(spec);
  }
}
