<?php
/**
* Spawn Framework
*
* Array
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Arr 
{

	/**
	*check whether the array is assoc
	*
	*@param - array - array to check
	*@return bool
	*/
	public static function isAssoc(array $val)
	{		
		return (array_keys(array_keys($val)) !== array_keys($val))? true : false;
	}
	
	/**
	*@param - array - array to check
	*@return bool
	*/
	public static function isArray($value)
	{
		if (is_array($value)){
			return TRUE;
		}else{
			return (is_object($value) AND $value instanceof Traversable);
		}
	}
	 
	/**
	*return param of array 
	*
	*@param - array assoc
	*@param - string - name of array to return
	*@param - string -value to return if self value not exists
	*@return bool
	*/ 
	public static function get(array $arr, $val, $or = null)
	{
		return ( array_key_exists($val, $arr) )? $arr[ $val ] : $or;
	}
	
	/**
	*replace array to assoc array with param
	*
	*@param - array
	*@param - string - value to assoc
	*@return array assoc
	*/ 
	public static function fill(array $arr, $val = '')
	{
		$assoc = array();	
		foreach($arr as $key){
			$assoc[ $key ] = $val;
		}
		return $assoc;
	}
	
	/**
	*push param to any id in array 
	*
	*@param - array
	*@param - string - value to push
	*@param - int - push position
	*@return array
	*/ 
	public static function push(array $arr, $value, $nr)
	{
		$arr = array_values($arr);
		$arra = array();
		$memo1 = null;
		$memo2 = null;
		$count = count($arr);
		for($i = 0; $i <= $count; $i++){
			if($memo1 !== null){ 
				if( array_key_exists($i, $arr) ) $memo2 = $arr[ $i ];
				$arr[ $i ] = $memo1;
				$memo1 = $memo2;			
			}
			if($nr === $i ){ 
				if( array_key_exists($i, $arr) ) $memo1 = $arr[$i]; 
				$arr[ $i ] = $value;
			}
			$arra[] = $arr[$i];
		}
		return $arra;
	}
	
	
	/**
	*update array to params
	*
	*@param - array | array assoc
	*@param - array - if this params not exists get it
	*@param - string - is first array is assoc - value to new params
	*@return array | array assoc
	*/ 
	public static function update(array $arr, array $update, $val = '')
	{
		if(self::isAssoc($update)){
			foreach($update as $key => $val){			
				if( !array_key_exists($key, $arr) ) $arr[ $key ] = $val;
			}
			return $arr;
		}
		foreach($update as $key ){		
			if( !array_key_exists($key, $arr) ) $arr[ $key ] = $val; 
		}
		return $arr;
	}
	
	/**
	*Pick one or more random entries out of an array
	*
	*@param array $arr
	*@param int $num
	*@return mixed
	*/
	public static function rand( array $arr, $num )
	{
		return array_rand( array_flip($arr), $num );		
	}
	
}//array
