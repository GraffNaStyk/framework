<?php

namespace App\Facades\Helpers;

use App\Facades\Storage\Storage;

class Serializer
{
	private const SERIALIZE_PATH = 'serialize/';
	
	public static function serialize(object $object, string $objectName): void
	{
		Storage::private()
			->make(self::SERIALIZE_PATH)
			->put('/'.static::SERIALIZE_PATH.'/'.self::normalizeName($objectName), serialize($object));
	}
	
	public static function deserialize(string $objectName): object
	{
		$object = unserialize(Storage::private()->get(static::SERIALIZE_PATH.'/'.self::normalizeName($objectName)));
		Storage::private()->remove(static::SERIALIZE_PATH.'/'.self::normalizeName($objectName));
		return $object;
	}
	
	public static function has(string $objectName): bool
	{
		return is_readable(storage_path('/private/'.static::SERIALIZE_PATH.'/'.self::normalizeName($objectName)));
	}
	
	private static function normalizeName(string $objectName)
	{
		return str_replace("\\", '_', $objectName);
	}
}
