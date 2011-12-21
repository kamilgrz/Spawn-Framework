<?php
/**
* Spawn Framework
*
* Date
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Date
{

	/**
	*return actual date
	*
	*@param bool $His
	*@return string
	*/
	public static function now($His = false)
	{
		return (false == $His)? date('Y-m-d') : date('Y-m-d H:i:s');
	}
	
	/**
	*add X day to date
	*
	*@param string  $date
	*@param string $day 
	*@return string
	*/
	public static function addDate($date, $day)
	{
		$sum = strtotime(date('Y-m-d', strtotime($date)) . ' +' . $day . ' days');
		$dateTo = date('Y-m-d', $sum);
		return $dateTo;
	}
	
	/**
	*check whether the year is leap
	*
	*@param integer $year
	*@return bool
	*/
	public static function isLeapYear($year = null) 
	{
		$year = (null != $year)? $year : date('Y');
		return  (bool)date('L', strtotime( $year . '-1-1'));
	}
		
	/**
	* @param integer $time
	* @return string
	*/	
	public static function w3cDate($time=null){
		$time = (null != $time)? $time: time();
		$offset = date("O", $time);
		return date("Y-m-d\TH:i:s", $time) . substr($offset,0,3) . ':' . substr($offset, -2);
	} 	
	
	/**
	*check whether the string is date
	*
	*@param string date
	*@return bool
	*/
	public static function isDate($str)
	{
		$stamp = strtotime( trim($str) );
		if(!is_numeric($stamp)) return false; 
		$month = date( 'm', $stamp );
		$day   = date( 'd', $stamp );
		$year  = date( 'Y', $stamp );
		return checkdate($month, $day, $year);
	}
	
}
