export enum InputType {
    Text,
    TextArea,
    NewPassword,   // Придумываем новый пароль
    CheckPassword, // Проверяем пароль
}

export default class InputSchema
{
    name: string;
    type: InputType|null = null;
    label: string|null = null;
    order: number|null = null;

    public constructor(
        name: string,
        label: string|null,
        type: InputType|null = InputType.Text,
        order: number|null = null,
    ) {
        this.name = name;
        this.label = label;
        this.type = type;
        this.order = order;
    }
}
