<?php
/**
* Spawn Framework
*
* Class to filter data
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Filter
{
	
	/**
	* filtr data about xss
	*
	* @param string $str data to filtr
	* @return string
	*/
	public static function xss($str)
	{
		return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}	
	
	/**
	* filtr data about bad utf8
	*
	* @param string $str data to filtr
	* @return string
	*/
	public static function utf8($str)
	{
		return @iconv("utf-8", "utf-8//IGNORE", $str);
	}
	
	
}//Filter
