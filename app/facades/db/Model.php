<?php

namespace App\Facades\Db;


/**
 * @method Db connection(string $connection)
 * @method Db getDbName()
 * @method Db as(string $alias)
 * @method Db distinct()
 * @method Db selectGroup(array $values = [])
 * @method Db multiple()
 * @method Db insert(array $values)
 * @method Db select(array $values = [])
 * @method Db update(array $values)
 * @method Db delete()
 * @method Db where(string $item, string $is, ?string $item2)
 * @method Db orWhere(string $item, string $is, ?string $item2)
 * @method Db whereNull(string $item)
 * @method Db whereNotNull(string $item)
 * @method Db orWhereNull(string $item)
 * @method Db orWhereNotNull(string $item)
 * @method Db whereIn(string $item, array $items)
 * @method Db whereNotIn(string $item, array $items)
 * @method Db whereBetween(string $item, array $items)
 * @method Db raw(string $raw)
 * @method Db bind(array $data)
 * @method Db order(array $by, string $type = 'ASC')
 * @method Db group(string $group)
 * @method Db limit(int $limit)
 * @method Db offset(int $offset)
 * @method Db paginate(int $page)
 * @method Db create(array $values)
 * @method Db count(string $item)
 * @method Db increment(string $field, int $increment)
 * @method Db decrement(string $field, int $decrement)
 * @method Db join(string $table, string $value1, string $by, string $value2, bool $isRigidly = false)
 * @method Db leftJoin(string $table, string $value1, string $by, string $value2, bool $isRigidly = false)
 * @method Db rightJoin(string $table, string $value1, string $by, string $value2, bool $isRigidly = false)
 * @method Db lastId()
 * @method Db debug()
 * @method Db query(string $query)
 * @method Db getColumnsInfo()
 * @method Db startBracket()
 * @method Db endBracket()
 * @method Db getEnumValues(string $field)
 * @method Db getConnectionName()
 */
abstract class Model
{
	public static function __callStatic(string $name, array $arguments)
	{
		return self::resolveMagicCall($name, $arguments);
	}
	
	public function __call(string $name, array $arguments)
	{
		return self::resolveMagicCall($name, $arguments);
	}
	
	private static function resolveMagicCall(string $name, array $arguments)
	{
		$db = new Db(get_called_class());
		$db->connect();

		if (isset($arguments[1])) {
			return call_user_func_array([$db, $name], $arguments);
		} else {
			return $db->$name($arguments[0] ?? $arguments);
		}
	}
}
