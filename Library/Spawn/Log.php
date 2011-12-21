<?php
/**
* Spawn Framework
*
* Log
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Log
{
    public static $path = 'Bin/Log/';    
    	
	/**
	*add message to log + create file log if not exists 
	*
	*@param string $log (message)
	*@param string $file name - if null use date Y-m-d to name
	*/
	public static function add($log, $file = null)
	{
		$file = ($file == null)? 'log-' . date('Y-m-d') . '.php' : $file;
		if(!file_exists(ROOT_PATH . self::$path . $file)){
			touch(ROOT_PATH . self::$path . $file);
		}	
		$log = '[ ' . date('Y-m-d H:i:s') . ' ] ' . \Sf\Filter::utf8($log) . PHP_EOL;
		file_put_contents(ROOT_PATH . self::$path . $file, $log, FILE_APPEND);
	}
	
	/**
	*load log
	*
	*@param string $name file name + path
	*@param bool $sf name generate with framework? (Y-m-d)
	*@return string|null
	*/
	public static function get($name, $sf = true)
	{
		$name = (true == $sf)? 'log-' . $name . '.php' : $name;
		if( file_exists(ROOT_PATH . self::$path . $name) ){
			return file_get_contents(ROOT_PATH . self::$path . $name);
		}
		return null;
	}
	
}//log
