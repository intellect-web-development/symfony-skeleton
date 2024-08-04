import Violations from "@/api/common/Violations";

export default interface FormPayloadInterface
{
    validate(): Violations;
}
