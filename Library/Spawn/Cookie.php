<?php
/**
* Spawn Framework
*
* Cookie
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Cookie
{

	/**
         *
         * @param string $name
         * @param string $val
         * @param integer $cookieTime
         * @param string $path
         * @param string $domain
         * @param bool $secure
         * @param bool $httponly
         */
	public function set($name, $val = true, $cookieTime = 0, $path = '', $domain = '', $secure = false, $httponly = false)
	{
		$cookieTime = ($cookieTime > 0 )? time() + $cookieTime: 0;
		setcookie($name, $val, $cookieTime, $path, $domain, $secure, $httponly);
	}
	
	/**
         *
         * @param string $name
         * @param string $or
         * @return string
         */
	public function get($name, $or = null){
		return ( isset($_COOKIE[ $name ]) )? $_COOKIE[ $name ] : $or;
	}
	
	/**
         *
         * @param string $name
         */
	public function delete($name)
	{
		if(isset($_COOKIE[ $name ])){
			unset($_COOKIE[ $name ]);
		}
	}

        /**
         *
         */
	public function deleteAll()
	{
		if(isset($_COOKIE)){
			foreach($_COOKIE as $name){
				unset($_COOKIE[$name]);
			}
			unset ( $_COOKIE );
		}
	}
	
}//cookie
