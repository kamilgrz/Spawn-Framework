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
namespace Spawn\Cache;
class Apc implements CacheInterface
{
        /**
         *
         * @param string $key
         * @return bool
         */
	public function exists($key)
	{
        if(function_exists('apc_exists')){
		    return apc_exists($key);
        }
        return (bool)apc_fetch($key);
	}

        /**
         *
         * @param string $key
         * @param mixed $val
         * @return bool
         */
        public function set($key, $val)
        {
            if($this->exists($key)){
                apc_delete($key);
            }
            return apc_add($key, $val);
        }

        /**
         *
         * @param string $key
         * @param mixed $val
         * @return bool
         */
        public function add($key, $val)
        {
            return apc_add($key, $val);
        }

        /**
         *
         * @param string> $key
         * @return bool
         */
        public function delete($key)
        {
            return apc_delete($key);
        }

        /**
         *
         * @param string $key
         * @return mixed
         */
        public function get($key)
        {
            return apc_fetch($key);
        }

        /**
         *
         * @param string $type
         * @return bool
         */
        public function clear($type='user')
        {
            return apc_clear_cache($type);
        }

        /**
         *
         * @param string $name
         * @return string|bool
         */
        public function dec($name)
        {
            return apc_dec($name);
        }

        /**
         *
         * @param string $name
         * @return string|bool
         */
        public function inc($name)
        {
            return apc_inc($name);
        }

}//apc
