<?php
/**
* Spawn Framework
*
* user acl group
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Acl
*/
namespace Spawn\Acl;
class Group
{
    /**
     * group name
     * @var string
     */
    protected $_name;

    /**
     * roles obj
     * @var array
     */
    protected $_role = array();
    
    /**
    * @var array
    */
    protected $_inherit = array();

    /**
     * declare group name
     * @param string $name
     */
    public function __construct($name)
    {
        $this -> _name = $name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setRole(Role $role)
    {
        $name = $role -> getName();
        $this -> _role[ $name ] = $role;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return $this
     */
    public function removeRole($name)
    {
        if( isset($this -> _role[ $name ]) ){
            unset($this -> _role[$name]);
        }
        return $this;
    }
    
    /**
     * role isset in this group?
     *
     * @param string $name
     * @return bool
     */
    public function roleIsset($name)
    {
        return (bool) isset($this -> _role[ $name ]);
    }

    /**
     * get group roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this -> _role;
    }

    /**
     * get role - sf_acl_role
     * @param string $name
     * @return \Spawn\Acl\Role|false
     */
    public function getRole($name)
    {
        if(isset($this -> _role[$name])){
            return $this -> _role[$name];
        }
        return false;
    }

    /**
     * set new group name
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this -> name = $name;
        return $this;
    }

    /**
     * get group name
     *
     * @return string
     */
    public function getName()
    {
        return $this -> _name;
    }
    
    /**
    * @param string
    * @return $this
    */
    public function setInherit($name)
    {
        $this -> _inherit[] = $name;
        return $this;
    }
    
    /**
    * @return array
    */
    public function getInherit()
    {
        return $this -> _inherit;
    }

    /**
     * get group name
     * 
     * @return string
     */
    public function  __toString() {
        return $this -> _name;
    }

}//group
