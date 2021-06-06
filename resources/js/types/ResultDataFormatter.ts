import Model from '@/types/Models/Model'

type ResultDataFormatter<TModel extends Model> = (
  data: unknown,
  rowData: TModel,
) => string | number

export default ResultDataFormatter
