<?php
/**
* Spawn Framework
*
* Translate
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Translate
*/
namespace Spawn;
abstract class Translate 
{
	/**
	* assoc array width data to transtale
	* @var array
	*/
	protected $_lang = array();
	
	/**
	* core lang
	* @var string
	*/
	protected $_core;
	
	/**
	* @param string
	* @return object
	*/
	public static function factory($name)
	{
            $name = ($name = 'Array')? 'Arr' : $name;
		$name = '\Spawn\Translate\\'.$name;
		$obj = new $name();
		return $obj;
	}
	
	/**
	*load array to translate stack
	*
	*@param string|array $value
	*@param string $lang
	*@return $this
	*/
	abstract public function load($value, $lang);
	
				
	/**
	*set/modyfication values in $lang array 
	*
	*@param array $value
	*@param string $lang
	*@return $this
	*/	
	public function setParam( array $value, $lang)
	{
		$this -> _lang[ $lang ] = array_merge($this -> _lang[ $lang ], $value);
		
		return $this;
	}
		
	/**
	*declare lang to translate
	*
	*@param string $lang
	*@return $this
	*/	
	public function setLang($lang)
	{
		$this -> _core = $lang;
		
		return $this;
	}
	
	/**
	*translate string
	*
	*@param string $msg - massage to translate
	*@param string $msgLang - massage lang (optional)
	*@return string
	*/
	public function translate($msg, $msgLang = null)
	{		
		if($msgLang != null){
			$msg = str_replace( 
				array_values($this -> _lang[ $msgLang ]), 
				array_keys($this -> _lang[ $msgLang ]), 
				$msg
			);	
		}
		
		$translateMsg = str_replace( 
			array_keys($this -> _lang[ $this -> _core ]), 
			array_values($this -> _lang[ $this -> _core ]), 
			$msg
		);
		
		return $translateMsg;
	}
	
	/**
	*translate uri (create param to sf_request)
	*use this method in bootstrap
	*/
	public function translateUri()
	{
		$_SERVER['REQUEST_URI'] = str_replace( 
			array_values($this -> _lang[ $this -> _core ]), 
			array_keys($this -> _lang[ $this -> _core ]), 			
			$_SERVER['REQUEST_URI']
		);			
	}
}
