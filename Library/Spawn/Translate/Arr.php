<?php
/**
* Spawn Framework
*
* translate
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Translate
*/
namespace Spawn\Translate;
class Arr extends \Spawn\Translate
{
	/**
	*load array to translate stack
	*
	*@param string|array $value
	*@param string $lang
	*/
	public function load($value, $lang)
	{
		if( is_string($value) ){
			$value = include(ROOT_PATH . $value); 
		}
		
		$this -> _lang[ $lang ] = $value;
		$this -> _core = $lang;
		return $this;
	}
	
}
