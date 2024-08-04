import sortModes from "@/api/common/filter/constants/sortModes";
import Sort from "@/api/common/filter/sort/Sort";

export default class SortCollection {
    constructor(
      public sorts: Sort[]
    ) {
    }

    toParams() {
        let sorts: string[] = [];
        this.sorts.map(sort => {
            let prefix = sort.direction === sortModes.DESC ? '-' : '';
            sorts.push(`${prefix}${sort.field}`);
        })
        return sorts.join(',')
    }
}
