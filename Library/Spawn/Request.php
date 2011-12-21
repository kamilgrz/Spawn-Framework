<?php
/**
* Spawn Framework
*
* Request
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Request
*/
namespace Spawn;
class Request
{
    /**
     *
     * @var Request\Uri
     */
    public $uri;
	
	/**
         * return $_GET param
         * @param string $pr
         * @param string $or
         * @return string
         */
	public function get($pr, $or = null)
	{
		if($_GET){			
			$par = ( isset($_GET[ $pr ]) )? Filter::utf8($_GET[ $pr ]) : $or;
		}else{
			$par = $or;
		}	
		return $par;
	}
	
	/**
         * return $_POST param
         * @param string $pr
         * @param string $or
         * @return string
         */
	public function post($pr, $or = null)
	{
		if($_POST){			
			$par = ( isset($_POST[ $pr ]) )? Filter::utf8($_POST[ $pr ]) : $or;
		}else{
			$par = $or;
		}	
		return $par;
	}
	
	/**
	* get $_FILES param 
	* use if $_FILES[$name] is array with many files
	*
	* @param string $name param name
	* @param integer $i
	* @return array
	*/
	public function getFile($name, $i)
	{
		if( !isset($_FILES[$name]['tmp_name'][$i]) ){
			return false;
		}
		
		return array(
			'name'     =>  Filter::utf8($_FILES[$name]['name'][$i]),
			'type'     =>  Filter::utf8($_FILES[$name]['type'][$i]),
			'tmp_name' =>  Filter::utf8($_FILES[$name]['tmp_name'][$i]),
			'error'    =>  Filter::utf8($_FILES[$name]['error'][$i]),
			'size'     =>  Filter::utf8($_FILES[$name]['size'][$i])
	
		);
	}
	
			
	/**
         *
         * @return string
         */
	public function referer()
	{			
		return Filter::utf8($_SERVER['HTTP_REFERER']);
	}	
	
	/**
         *
         * @return string
         */
	public function ip()
	{			
		return Filter::utf8($_SERVER['REMOTE_ADDR']);
	}
	
	/**
         * @return bool
         */
	public function isAjax()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
			if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				return true;
			}	
		}
		return false;
	}
	
}//request
