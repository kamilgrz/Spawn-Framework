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
    public function __get($name)
    {
        $model = $this->get($name);
        return $model;
    }

    /**
     * @param string $name
     * @param mixed $val
     */
    public function __set($name,$val)
    {
        $this->set($name, $val);
    }

    /**
     * @param $name
     * @param $args
     * @return mixed
     * @throws DIException
     */
    public function __call($name, $args)
    {
        if(is_callable(self::$_data[$name])) {
            $cal = self::$_data[$name];
            self::$_data[$name] = $cal($args);
        }elseif(is_string(self::$_data[$name])){
            $rc = new \ReflectionClass(self::$_data[$name]);
            self::$_data[$name] = $rc->newInstanceArgs($args);
            return self::$_data[$name];
        }elseif(!isset(self::$_data[$name])) {
            Throw new DIException('DI: '.$name.' not found!');
        }else{
            Throw new DIException('DI: '.$name.' can\'t be used!');
        }
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
     * @param string $name
     * @return object
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
     * @return mixed
     */
    public function getValue($name)
    {
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

    /**
     * @param string $name
     * @return $this
     */
    public function delete($name)
    {
        if($this->has($name)) {
            unset(self::$_data[$name]);
        }
        return $this;
    }

    /**
     * @param bool $run
     * @return array
     */
    public function getAll($run = false)
    {
        if(false == $run) return self::$_data;

        $data = array();
        $keys = array_keys(self::$_data);
        foreach($keys as $key) {
            $data[$key] = $this->get($key);
        }
        return $data;
    }
}
class DIException extends \Exception{}

