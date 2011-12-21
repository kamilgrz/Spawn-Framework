<?php
/**
* Spawn Framework
*
* uri manager
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Request
*/
namespace Spawn\Request;
class Uri extends \Spawn\Request\Uri\Core
{     
	
	/**
         *
         * @param integer $id
         * @param string $or
         * @return string
         */
	public function param($id, $or = null)
	{	
		return ( isset(self::$_param[ $id ]) && self::$_param[ $id ] != '' )? self::$_param[ $id ] : $or;			
	}
	
	/**
         *
         * @param string|integer $val
         * @param string $val2
         * @return $this
         */
	public function setParam($val, $val2 = null)
	{
		if(null == $val2){
			self::$_param[] = $val;
			return count(self::$_param)-1;
		}
		self::$_param[ $val ] = $val2;	
		return $this;			
	}
	
	/**
	* get param
	*
	* @param string $name
	* @param string $or
	* @return string
	*/
	public function getByName($name, $or = null)
	{
		$id = self::$_paramName[ $name ];
		return $this -> param($id, $or);
	}
	
	/**
	* set param name
	*
	* @param integer $id
	* @param string $name
	* @return $this
	*/
	public function setParamName($id, $name)
	{
		self::$_paramName[ $name ] = $id;
		return $this;
	}
	
	/**
         *
         * @param string $val
         * @param integer $nr
         * @return $this
         */
	public function paramPush($val, $nr)
	{
		self::$_param = \Spawn\Arr::push(self::$_param, $val, $nr);
		return $this;
	}
	
	/**
         *
         * @param integer $id
         * @param string $val
         * @return $this
         */
	public function paramReplace($id, $val)
	{
		self::$_param[ $id ] = $val;	
		return $this;
	}
	
	/**
         * @return array
         */
	public function getAll()
	{
		return self::$_param;
	}
	    
}
