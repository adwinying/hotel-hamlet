import Model from '@/types/Models/Model'
import ResultDataFormatter from '@/types/ResultDataFormatter'

type ResultTableFormatter<TModel extends Model> = Record<
  string,
  ResultDataFormatter<TModel>
>

export default ResultTableFormatter
