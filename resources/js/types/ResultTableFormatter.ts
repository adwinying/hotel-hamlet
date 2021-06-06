import Model from '@/types/Models/Model'
import ResultDataFormatter from '@/types/ResultDataFormatter'

interface ResultTableFormatter<TModel extends Model> {
  [key: string]: ResultDataFormatter<TModel>
}

export default ResultTableFormatter
