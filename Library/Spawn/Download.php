<?php
/**
* Spawn Framework
*
* Class to Download
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Download
{

	/**
	*declare headers to download file
	*
	*@param string $mime type
	*@param string $name file name
	*@param integer $size file size
	*/
	protected static function headers($mime, $name, $size)
	{
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.$name.'"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '. $size);
		header('Expires: 0');
		
		//MSIE or Normal header:
		if ( strpos('msie', strtolower($_SERVER['HTTP_USER_AGENT']) ) ){
			//msie
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		}else{
			//normal
			header('Pragma: no-cache');
		}
	}

	/**
	*download file
	*
	* @param string $file name + path
	* @param string $newName
	* @return bool
	*/
	public static function file($file, $newName = null)
	{
		if(file_exists($file) && is_file($file) ){
			$finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo -> file($file);
			$size = filesize($file);
			$name = basename($file);
				
			$name = ($newName == null) ? $name : $newName;
			
			//core headers
			self::headers($mime,$name,$size);
			$f = fopen($file, 'rb');
			fpassthru($f);
			fclose($f);
				
			return true;		
		}
		return false;
	}
	
	/**
	*download file with rate
	*
	*@param string $file name + path
	*@param string $newName file name
	*@param float $download_rate
	*/
	public static function rate($file, $newName=null, $download_rate = 20.5)
	{
		if(file_exists($file) && is_file($file))
		{
			$finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo -> file($file);
			$size = filesize($file);
			$name = basename($file);
				
			$name = ($newName == null) ? $name : $newName;
			self::headers($mime, $name, $size);
			flush();
			$file = fopen($file, "r");
			while(!feof($file))
			{
				print fread($file, round($download_rate * 1024));
				flush();
				sleep(1);
			}
			fclose($file);
		}
	}
	
	/**
	*download data as file
	*
	*@param string $data to download
	*@param string $fileName file name
        *@param string $mime
	*/
	public static function data($data, $fileName, $mime)
	{		
		$size = strlen($data);
				
		self::headers($mime,$fileName,$size);
		
		echo $data;				
	}
		
}//download
