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
class Xml extends \Spawn\Translate
{		
	/**
	*load xml to translate stack
	*
	*@param string $val
	*@param string $lang
	*/
	public function load($xml, $lang)
	{
		$xml = (is_file($xml) or \Spawn\Valid::url($xml))?  simplexml_load_file($xml) : simplexml_load_string($xml);
		
		$valArray = array();		
		foreach($xml -> message as $key){	
		    $core = (string)$key -> core;		
			$valArray[ $core ] = $key -> translate;
		}
		
		$this -> _lang[ $lang ] = $valArray;
		$this -> _core = $lang;
		return $this;
	}
	
}
