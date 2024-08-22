import BaseOutputContract from '@/api/common/BaseOutputContract';
import { UserEntity } from "./UserEntity";

export default class UserCommonOutputContract extends BaseOutputContract {
    public constructor(
        status: number,
        ok: boolean,
        data: UserEntity,
        messages: object,
    ) {
        super(
            status,
            ok,
            new UserEntity(data),
            messages
        );
    }
}
