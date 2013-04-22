<?php
/**
 * Spawn Framework
 *
 * Dependency Injection
 *
 * @author  Paweł Makowski
 * @copyright (c) 2013 Paweł Makowski
 * @license http://spawnframework.com/license New BSD License
 */

namespace Spawn;


class DI
{
    /**
     * @var array
     */
    protected static $_data = array();

    /**
     * @param string $key
     * @param mixed $val
     * @return $this
     */
    public function set($key, $val)
    {
        self::$_data[$key]=$val;
        return $this;
    }

    /**
     * @param $name
     * @return DI
     * @throws DIException
     */
    public function get($name)
    {
        if(!isset(self::$_data[$name])) {
            Throw new DIException('DI: '.$name.' not found!');
        }elseif(is_callable(self::$_data[$name])) {
            $cal = self::$_data[$name];
            return $cal();
        }elseif(is_string(self::$_data[$name])) {
            return new self::$_data[$name]();
        }elseif(is_object(self::$_data[$name])) {
            return self::$_data[$name];
        }else{
            $rc = new \ReflectionClass(self::$_data[$name][0]);
            return $rc->newInstanceArgs(self::$_data[$name][1]);
        }
    }
}
class DIException extends \Exception{}