type ResultTableFormatter<TModel extends object> = {
  [key in keyof TModel]?: (
    data: TModel[key],
    rowData: TModel,
  ) => string | number
}

export default ResultTableFormatter
