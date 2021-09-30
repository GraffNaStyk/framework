<?php

namespace App\Facades\Db;

class Entity
{
	private static array $reserved = ['table', 'trigger'];
	
	public static function resolve($result, string $model, bool $singleResult = false)
	{
		$entity     = (new \ReflectionClass($model))->getProperties();
		$properties = new \StdClass();

		foreach ($entity as $property) {
			$doc = static::parseDocBlock($property->getDocComment());

			if (isset($doc['column']) && ! in_array($doc['column'], static::$reserved, false)) {
				$properties->{$doc['column']} = $property->name;
			}
		}

		if ($singleResult) {
			foreach ($result as $key => $value) {
				if (isset($properties->{$key})) {
					$result->{$properties->{$key}} = $value;
					unset($result->{$key});
				}
			}
		} else {
			foreach ($result as $item) {
				foreach ($item as $key => $value) {
					if (isset($properties->{$key})) {
						$item->{$properties->{$key}} = $value;
						unset($item->{$key});
					}
				}
			}
		}

		return $result;
	}
	
	private static function parseDocBlock(string $docblock): array
	{
		$result   = [];
		$docblock = preg_split("/(\r?\n)/", $docblock);
		
		array_shift($docblock);
		array_pop($docblock);

		foreach ($docblock as $item) {
			$item = explode('=', ltrim(trim(str_replace('*', '', $item)), '@'));
			$result[$item[0]] = $item[1];
		}

		return $result;
	}
}
