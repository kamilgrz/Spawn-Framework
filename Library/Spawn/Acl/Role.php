<?php
/**
* Spawn Framework
*
* user acl role
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Acl
*/
namespace Spawn\Acl;
class Role
{
    /**
     * role name
     * @var string
     */
    public $name;
    

    /**
     * declare role name
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this -> name = $name;
    }

    /**
     * get role name
     * @return string
     */
    public function getName()
    {
        return $this -> name;
    }

    /**
     * get role name
     *
     * @return string
     */
    public function  __toString() {
        return $this -> name;
    }

}//role
