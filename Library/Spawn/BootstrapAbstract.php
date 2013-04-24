<?php
/**
 * Spawn Framework
 *
 * Bootstrap
 *
 * @author  Paweł Makowski
 * @copyright (c) 2013 Paweł Makowski
 * @license http://spawnframework.com/license New BSD License
 */
namespace Spawn;
abstract class bootstrapAbstract
{
    protected $_use = array();

    protected $_di;

    public function __construct($di)
    {
        $this->_di = $di;
    }

    public function start()
    {
        foreach($this->_use as $key) {
            $name = '_'.$key;
            $this->$name($this->_di);
        }
    }

}
