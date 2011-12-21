<?php
/**
* class to operations in config
*
* @author  Paweł Makowski
* @copyright (c) 2010 Paweł Makowski
* @license http://spawnframework.com/license
*/
namespace Spawn;
class Config
{
   /**
   * @var array
   */
    protected static $cfg = array();
        
    /**
    * @var string
    */    
    public $path = 'Bin/Config/';

    /**
    * @var string
    */  
    public $_name;

    public function __construct($name)
    {
        $this->_name = $name;
        if( !isset( self::$cfg[ $name ] ) ){
            if( file_exists( ROOT_PATH . $this->path . $name . '.php' ) ){
		        self::$cfg[ $name ] = include( ROOT_PATH . $this->path . $name . '.php' );
            }
        }
    }

    /**
    * @param string $name
    * @return Config
    */ 
    public static function load($name)
    {
	    return new Config($name);
    }
	
	/**
    * @param string $param
    * @param mixed $or
    * @return mixed
    */ 
    public function get($param, $or = null)
    {
	    return ( isset( self::$cfg[ $this->_name ][ $param ] ) )? self::$cfg[ $this->_name ][ $param ] : $or;
    }
	
	/**
    * @param string $param
    * @return bool
    */ 
    public function paramIsset($param)
    {
	    return isset( self::$cfg[ $this->_name ][ $param ] );
    }
	
	/**
    * @param string $param
    * @param mixed $val
    * @return Config
    */ 
    public function set($param , $val)
    {
    	self::$cfg[ $this->_name ][ $param ] = $val;
        return $this;
    }
	
	/**
    * @return array
    */ 
    public function getAll()
    {
	    return self::$cfg[ $this->_name ];
    }
	
	/**
    * @return Config
    */ 
    public function clear()
    {
    	if( isset( self::$cfg[ $this->_name ] ) ){
            unset( self::$cfg[ $this->_name ] );
        }
        return $this;
    }
    
}//Config
