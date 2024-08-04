import type {ObjectSchema} from "yup";
import Violations from "@/api/common/Violations";

export function validate(
    schema: ObjectSchema<any>,
    object: object,
): Violations {
    try {
        schema.validateSync(object, { abortEarly: false });
    } catch (err) {
        let violations = {};
        // @ts-ignore
        let errors = err.errors;
        for (let i in errors) {
            let error = errors[i];
            let firstSpaceIndex = error.indexOf(' ');
            // @ts-ignore
            violations[error.substring(0, firstSpaceIndex)] = error.substring(firstSpaceIndex + 1);
        }

        return new Violations(violations);
    }

    return new Violations();
}