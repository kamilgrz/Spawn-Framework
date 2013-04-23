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
     * @param $name
     * @return object
     */
    function __get($name)
    {
        $model = $this->get($name);
        return $model;
    }

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
            self::$_data[$name] = $cal();
        }elseif(is_string(self::$_data[$name])) {
            self::$_data[$name] = new self::$_data[$name]();
        }elseif(is_array(self::$_data[$name])) {
            $rc = new \ReflectionClass(self::$_data[$name][0]);
            self::$_data[$name] = $rc->newInstanceArgs(self::$_data[$name][1]);
        }else {
        }
        return self::$_data[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset(self::$_data[$name]);
    }
}
class DIException extends \Exception{}
