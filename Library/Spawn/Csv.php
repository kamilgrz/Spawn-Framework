<?php
/**
* Spawn Framework
*
* Csv
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Csv 
{
	
	/**
	*parse array to csv string
	*
	*@param array $data co parse
	*@param string $delimiter
	*@param string $enclosure
	*@return string
	*/
	public static function parse(array $data, $delimiter = ',', $enclosure = '"')
	{
		$csv = array();
		foreach($data as $key)
		{
			$parseKey = array();
			foreach($key as $val){				
				$val = self::parseVal($val, $enclosure);
				$parseKey[] = (is_string($val))? $enclosure.$val.$enclosure : $val;
			}	
			$csv[]=implode($delimiter, $parseKey);
		}
		
		return implode("\n", $csv);
	}
	
	/**
	*parse val to csv string
	*
	*@param string $val
	*@param string $enclosure
	*@return string
	*/
	public static function parseVal($val, $enclosure = '"')
	{
		if(is_string($val)){
			if($enclosure == '"') $val = str_replace('"', '""', $val);
			if($enclosure == '\'') $val = str_replace('\'', '\\\'', $val);			
			$val = str_replace("\n", ' ', $val);
		}
		return $val;
	}
	
	/**
	*return and parse to array csv file
	*
	*@param string $name
	*@param string $delimiter
	*@param string $enclosure
	*@return array
	*/
	public static function get($name, $delimiter = ',', $enclosure = '"')
	{
		$row = array();
		
		$handle = fopen($name, 'r');
		
		$size = filesize($name)+1; 
		
		while(!feof($handle)){
			$row[] = fgetcsv($handle, $size, $delimiter, $enclosure);
		}
		
		return $row;
	}
	
	/**
	*parse data(array) to csv and save in file
	*
	*@param string $name
	*@param array $data to parse and save
	*@param string $delimiter
	*@param string $enclosure
	*/
	public static function set($name, array $data, $delimiter = ',', $enclosure = '"')
	{		
		$handle = fopen($name, 'w');
		
		//parse data and put in file
		foreach ($data as $key) {
		
			//remove \n
			$parseKey = array();
			foreach($key as $val){				
				$parseKey[] = str_replace("\n", ' ', $val);;
			}
			$key = $parseKey;			
			
			//put to file
			fputcsv($handle,  $key, $delimiter, $enclosure );
		}

		fclose($handle);
	}
	
}//csv	
