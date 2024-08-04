import { sortModesArray } from "@/api/common/filter/constants/sortModes";

export default class Sort {
    field;
    direction;

    /**
     * @param {string} field
     * @param {string[sortModesArray]} direction
     */
    constructor(
      field: string,
      direction: string
    ) {
        if (sortModesArray.indexOf(direction) === -1) {
            throw new Error('Invalid sort mode');
        }

        this.field = field;
        this.direction = direction;
    }
}
