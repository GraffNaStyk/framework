##Console
| Typ | Opis |
| ------ | ------ |
| Crontab | time php path/to/console cronjob method - run,make fileName |
| Controller | php console controller method - run fileName |
| Model | php console model make fileName Table |
| Migrate | php console migrate method - up,down,make,dump fileName table |
| Middleware | php console middleware make fileName |
| Rule | php console rule make fileName |


##Model
```
Model::insert(array)
->duplicate(ON DUPLICATE KEY UPDATE)
->exec()

Model::update(array)->where(param1, is, param2)->exec()

Model::select(array)
->as(alias for table)
->distinct()
->join(table, param1, is, param2, rigidly)
->leftJoin(table, param1, is, param2, rigidly)
->rightJoin(table, param1, is, param2, rigidly)
->where(param1, is, param2)
->orWhere(param1, is, param2)
->whereNull(param)
->whereNotNull(param)
->orWhereNull(param)
->orWhereNotNull(param)
->whereIn(array, item)
->whereNotIn(array, item)
->raw(query string)
->order(by, type)
->group(by)
->limit(limit)
->offset(offset)
->get()

Model::delete()->where(param1, is, param2)->exec()

Model::count(item)->where(param1, is, param2)->exec()

Model::increment(field, value)->where(param1, is, param2)->exec()

Model::decrement(field, value)->where(param1, is, param2)->exec()

Model::lastId()
```
