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
class Memcached implements CacheInterface
{
        /**
         * @var \memcached
         */
        public $cache;

        /**
         *
         * @var integer
         */
        public $expire = 0;

        /**
         *
         * @param string $host
         */
        public function __construct($host = 'localhost')
        {
            $this -> cache = new \Memcached();
            $this -> cache -> addServer($host, 11211);
        }

        /**
         *
         * @param string $host
         * @return bool
         */
        public function addServer($host)
        {
            return $this -> cache -> addServer($host, 11211);
        }

        /**
         *
         * @param array $servers
         * @return bool
         */
        public function addServers($servers)
        {
            return $this -> cache -> addServers($servers);
        }

        /**
         *
         * @param string $name
         * @return mixed
         */
        public function get($name)
	{
            return $this -> cache -> get($name);
	}

        /**
         *
         * @param string $name
         * @return bool
         */
        public function exists($name)
	{
            return (bool)$this -> cache -> get($name);
	}

        /**
         *
         * @param string $name
         * @param mixed $data
         * @return bool
         */
	public function set($name, $data)
	{
            return $this -> cache -> set($name, $data, $this -> expire);
	}

        /**
         *
         * @param string $name
         * @return bool
         */
	public function delete($name)
	{
            return $this -> cache -> delete($name);
	}

        /**
         *
         * @return bool
         */
	public function clean()
	{
            return $this -> cache -> flush();
	}

        /**
         *
         * @param string $name
         * @return bool
         */
	public function dec($name)
        {
            return $this -> cache -> decrement($name);
        }

        /**
         *
         * @param string $name
         * @return bool
         */
        public function inc($name)
        {
            return $this -> cache -> increment($name);
        }


}//memcached
