<?php
/**
* Spawn Framework
*
* Class to data storage
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Cache
*/
namespace Spawn\Cache;
interface CacheInterface{

    /**
     *
     * @param string $key
     * @param mixed $val
     */
    public function set($key, $val);

    /**
     *
     * @param string $key
     */
    public function get($key);

    /**
     *
     * @param string $name
     */
    public function delete($name);

    /**
     *
     * @param string $name
     */
    public function exists($name);
    
}
