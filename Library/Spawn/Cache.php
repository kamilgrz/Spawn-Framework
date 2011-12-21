<?php
/**
* Spawn Framework
*
* Class to cache data
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Cache
*/
namespace Spawn;
class Cache implements Cache\CacheInterface
{
	public $cache;

	public function __construct($name = 'File')
	{
		$name = '\Spawn\Cache\\'.$name;
		$this -> cache = new $name();
	}
	
	public function set($key, $val)
	{
		return $this -> cache -> set($key, $val);
	}
	
	public function get($key)
	{
		return $this -> cache -> get($key);
	}

        public function exists($key)
	{
		return $this -> cache -> exists($key);
	}
	
	public function delete($name)
	{
		return $this -> cache -> delete($name);
	}		
	
}//cache
