<?php
/**
* Spawn Framework
*
* Url
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Url
{
	
	/**
    * /controller/action
    *
    * @return string
    */
	public static function base()
	{	
		$uri = new Request\Uri;
		return '/' . $uri -> param(0) . '/' . $uri -> param(1);
	}	
	
	/**
    * create http:// or https:// link
    *
    * @param string $url
    * @param string $type
    * @param bool $endOfUri
    * @return string
    */
	public static function url( $url, $type='http', $endOfUri = false )
	{
		$isUrl=strpos($url,'://');
		if( false == $isUrl or $isUrl > 5 ) $url = $type.'://'.$url;
		if( true == $endOfUri) $url .= Config::Load('Uri') -> get('endOfUri');
		return $url;
	}
	
	/**
	*create page link /mylink.html etc.
	*
	*@param string $url
	*@return string 
	*/
	public static function site( $url )
	{
		if( 0 !== strpos($url,'/') ) $url = '/'.$url;
		$url = Config::Load('Uri') -> get('base') . $url . Config::Load('Uri') -> get('endOfUri');
		$url = str_replace('//', '/', $url);
		return $url;
	}
	
	/**
	*create url to links 
	*
	*@param array|string $name
	*@param string $val
	*@return string
	*/
	public static function get($name, $val=null, $base = true)
	{
		if( null !== $val ){
			$name = array($name => $val);
		}
			
		$param = array();
		$get = $_GET;
		foreach($name as $key => $val){
			if( isset($get[ $key ]) ){
				unset($get[ $key ]);
			}
			$param[] = $key . '=' . $val;			
		}
		
		$baseUrl = '?'.implode('&amp;', $param);	
		$hbq = http_build_query($get, '', '&amp;');
		$baseUrl .= ( strlen($hbq)>0 )?  '&amp;'.$hbq : '';	
		
		if( false == $base ){
			return $baseUrl;
		}
		
		$uri = new Request\Uri;	
		$baseUrl = '/' . implode('/', $uri -> getAll()) . Config::load('Uri') -> get('endOfUri') . $baseUrl;
		
		return $baseUrl;	
	}
	
	
	
	/**
	*create url to links 
	*get URI param to add/replace
	*
	*@param string $val
	*@param integer $id
	*@return string
	*/
	public static function uri( $val, $id = null )
	{
	    $uri = new Request\Uri;
		$param = $uri -> getAll();
		
		if(null !== $id){
			$param[ $id ] = $val;
		}else{
			$param[] = $val;
		}
		
		$baseUrl = '/' . implode('/', $param) . Config::load('Uri') -> get('endOfUri');
		if( isset($_GET) AND count($_GET) > 0 ){
			$baseUrl .= '?' . http_build_query($_GET, '', '&amp;');
		}		
		return $baseUrl;
	}
		
}//url
