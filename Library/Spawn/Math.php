<?php
/**
* Spawn Framework
*
* Math
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Math
{
	
	/**
	*convert rgb to hex
	*
	*@param integer $r
	*@param integer $g
	*@param integer $b
	*@return string
	*/
	public static function rgb2hex($r, $g, $b)
	{		
	   	 return sprintf( '#%02s%02s%02s', dechex( $r ), dechex( $g ), dechex( $b ) );
	}
	
	/**
	*convert  hex to rgb
	*
	*@param string $color
	*@return array
	*/
	public static function hex2rgb($color)
	{
    		$color = str_replace('#', '', $color);
    		if (strlen($color) != 6){ return array(0,0,0); }
    		$rgb = array();
    		for ($x = 0; $x < 3; $x++){
       			$rgb[$x] = hexdec(substr($color, (2*$x), 2));
   		}
    		return $rgb;
	}

	/**
	*convert dec to hex
	*
	*@param integer $dec
	*@return string
	*/
	public static function dec2hex($dec)
	{
		$hex = ($dec == 0) ? 0 : '';

		while ($dec > 0){
			$hex = dechex($dec - floor($dec / 16) * 16).$hex;
			$dec = floor($dec / 16);
		}

		return $hex;
	}
	
	/**
	*convert hex to dec
	*
	*@param string $number
	*@return integer
	*/
	public static function hex2dec($number)
	{
		$decvalues = array('0' => '0', '1' => '1', '2' => '2',
		       '3' => '3', '4' => '4', '5' => '5',
		       '6' => '6', '7' => '7', '8' => '8',
		       '9' => '9', 'A' => '10', 'B' => '11',
		       'C' => '12', 'D' => '13', 'E' => '14',
		       'F' => '15');
		$decval = '0';
		$number = strrev($number);
		for($i = 0; $i < strlen($number); $i++){
			$decval = bcadd(bcmul(bcpow('16',$i,0),$decvalues[$number{$i}]), $decval);
		}
		return $decval;
	}
	
	/**
	*get percent
	*
	*@param integer $number
	*@param integer $of
	*@param integer $precision
	*@return - int
	*/
	public static function percent($number, $of, $precision = 2 )
	{
		if($of == 0) return false;
		return round(($number / $of * 100), $precision);
	}

	
}//math
