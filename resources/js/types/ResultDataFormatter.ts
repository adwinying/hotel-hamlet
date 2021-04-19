type ResultDataFormatter = (
  data: unknown,
  rowData: Record<string, unknown>
) => string | number

export default ResultDataFormatter
