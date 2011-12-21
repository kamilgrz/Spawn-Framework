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
class Csv extends \Spawn\Translate
{	
	/**
         *
         * @var string
         */
	public $delimiter = ',';

        /**
         *
         * @var string
         */
	public $enclosure = '"';

		
	/**
	*load csv to translate stack
	*
	*@param string $val
	*@param string $lang
	*/
	public function load($value, $lang)
	{
		$csvArray = \Spawn\Csv::get($value, $this -> delimiter, $this -> enclosure);
		
		$valArray = array();
		
		foreach($csvArray as $val => $key){
			$valArray[ $key[0] ] = $key[1];
		}
		
		$this -> _lang[$lang] = $valArray;
		$this -> _core = $lang;
		return $this;
	}
	
}
