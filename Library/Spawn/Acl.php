<?php
/**
* Spawn Framework
*
* Access Control List
*
* @author  Paweł Makowski
* @copyright (c) 2010-2013 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Acl
*/
namespace Spawn;

use \Spawn\Acl\Role;
use \Spawn\Acl\Group;

class Acl 
{
	/**
         * @var array
         */
	protected $_role = array();

        /**
         *
         * @var array
         */
	protected $_group = array();	
	
	/**
	*add param to role stack
	*@param string|Role $role
	*@return $this
	*/
	public function addRole($role)
	{
        $role = (is_string($role))? new Role($role) : $role;
		$name = $role -> getName();
        $this -> _role[ $name ] = $role;
		
		return $this;
	}

        /**
         *
         * @param array $role
         */
        public function addRoles(array $role)
        {
            foreach($role as $key){
                $this -> addRole($key);
            }
        }

    /**
     * @param string|object $group
     * @param array $roles
     * @return $this
     */
    public function addGroup($group, array $roles = null)
	{
        $group = (is_string($group))? new Group($group) : $group;
        $name = $group -> getName();
		$this -> _group[ $name ] = $group;

        if(null != $roles) {
            foreach($roles as $key) {
                $this->setGroupRole($name, $key);
            }
        }

		return $this;
	}
	
	/**
         *
         * @param string $name
         * @param string $role
         * @return Acl
         */
	public function setGroupRole($name, $role)
	{
            $role = (is_string($role))? new Role($role) : $role;
            $this -> _group[ $name ] -> setRole($role);
            return $this;
	}	
	
	/**
         * inherit group
         * @param string $name
         * @param string $inherit
         * @return Acl
         */
	public function inherit($name, $inherit)
	{
		$this -> _group[ $name ] -> setInherit( $this -> _group[ $inherit ] -> getName() );
		return $this;
	}
	
	/**
	*return core roles
	*
	*@return array
	*/
	public function getRole()
	{
		return $this -> _role;
	}
	
	/**
         *
         * @param string $name
         * @return Group
         */
	public function getGroup($name){
		return $this -> _group[ $name ];
	}

        /**
         *
         * @return array
         */
        public function getGroups()
        {
            return $this -> _group;
        }
	
	
	/**
         *
         * @param string $name
         * @param string $role
         * @return Acl
         */
	public function removeGroupRole($name, $role)
	{
		$this -> _group[ $name ] -> removeRole($role);
		return $this;
	}
	
	/**
         *
         * @param string $name
         * @return Acl
         */
	public function removeGroup($name)
	{
		if( isset($this -> _group[ $name ]) ) unset($this -> _group[ $name ]);
		return $this;
	}
	
	/**
	*remove role
	*
	*@param string $name
	*@return $this
	*/
	public function removeRole($name)
	{
		if( isset($this -> _role[ $name ]) ) unset($this -> _role[ $name ]);
		return $this;
	}
	
	/**
         *
         * @param string|array $roles role or group name
         * @param string $find
         * @return <type>
         */
	public function isAllowed($roles, $find=null)
	{
		$roles = (is_array($roles) )? $roles : array($roles);
		foreach($roles as $name){		
			if( null == $find && isset($this -> _role[ $name ]) ){
                return true;
            }
			if( !isset($this -> _group[ $name ]) ){
			    return false;                    
			}
			if( $this -> _group[ $name ] -> roleIsset($find) ){
			    return true;
			}    
			$inherit = $this -> _group[ $name ] -> getInherit();
			$this -> find = $find;
			return $this -> _searchRoleInGroupInherit($inherit);	
		}
		return false;
	}
	
	protected function _searchRoleInGroupInherit($inherit)
	{   
	    foreach( $inherit as $key ){
		    if( isset($this -> _group[ $key ]) ){
		        if( $this -> _group[ $key ] -> roleIsset($this -> find) ){
		            return true;
		        }
		        $result = $this -> _searchRoleInGroupInherit( $this -> _group[ $key ] -> getInherit());
		        if(true == $result){
		            return true;
		        }
		    }		    
		}
		return false;	
	}

        /**
         *
         * @param string $name
         * @return bool
         */
        public function roleAllowed($name)
        {
            return (bool)isset($this -> _role[ $name ]);
        }

        /**
         *
         * @param string $name
         * @return bool
         */
        public function groupAllowed($name)
        {
            return (bool)isset($this -> _group[ $name ]);
        }
		
}//acl

