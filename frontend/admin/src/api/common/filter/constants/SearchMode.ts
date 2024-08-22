export enum SearchMode {
  IN = "in",
  LIKE = "like",
  RANGE = "range",
  EQUALS = "equals",
  NOT_IN = "not-in",
  IS_NULL = "is-null",
  NOT_NULL = "not-null",
  NOT_LIKE = "not-like",
  LESS_THAN = "less-than",
  NOT_EQUALS = "not-equals",
  GREATER_THAN = "greater-than",
  LESS_OR_EQUALS = "less-or-equals",
  GREATER_OR_EQUALS = "greater-or-equals",
}

export default SearchMode

// Для получения массива значений
export const searchModesArray = Object.values(SearchMode);
