<?php
/**
* Spawn Framework
*
* Registry
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Registry
{	
	/**
         * registered data
         * @var array
         */
	protected static $_values = array();

        /**
         * @var string
         */
	protected $_space;

        /**
         *
         * @param string $name
         * @return Registry
         */
	public static function load($name = 'Default')
	{
	    return new Registry($name);
	}

        /**
         * @param string $name
         */
	public function __construct($name = 'Default')
	{
	    $this -> _space = $name;
	}
		
	/**
	*set data to register
	*
	*@param string $name = 'default'
	*@param mixed $val
	*/	
	public function set($name, $val)
	{
		self::$_values[ $this -> _space ][ $name ] = $val;
	}	
	
	/**
         *
         * @param string $name
         * @param mixed $or
         * @return mixed
         */
	public function get($name, $or = null)
	{
		if( isset(self::$_values[ $this -> _space ][ $name ]) ){
			return self::$_values[ $this -> _space ][ $name ];
		}
		return $or;
	}
	
	/**
	* get all registered data
	*
	* @return array
	*/
	public function getAll()
	{
		return self::$_values[ $this -> _space ];
	}
		
	/**
         *
         * @param string $name
         * @return Registry
         */
	public function delete($name)
	{
		if( isset(self::$_values[ $this -> _space ][ $name ]) ){
			unset(self::$_values[ $this -> _space ][ $name ]);
		}
                return $this;
	}
	
	/**
         *
         * @return Registry
         */
	public function deleteAll()
	{
		self::$_values[ $this -> _space ] = array();
                return $this;
	}
	
	/**
         *
         * @param string $name
         * @return bool
         */
	public function isRegistered($name)
	{
		return ( isset(self::$_values[ $this -> _space ][ $name ]) )? true: false;
	}	
	
}//registry