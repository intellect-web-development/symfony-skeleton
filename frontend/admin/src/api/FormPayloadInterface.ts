import Violations from "@/api/Violations";

export default interface FormPayloadInterface
{
    validate(): Violations;
}
