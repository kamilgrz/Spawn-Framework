<?php
/**
* Spawn Framework
*
* Curl
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;

class Curl
{
	/**
    * @var curl handle
    */
	public $curl;
	
	/**
	*declare curl handle
	*
	*@param string $url
	*/
	public function __construct($url = '')
	{
		$this -> curl = curl_init($url);
	}	
	
	/**
	*declare curl handle
	*
	*@param string $url
	*/
	public function init($url)
	{
	    $this -> close();
	    $this -> curl = curl_init($url);
	    return $this;
	}
	
	/**
	*declare true if we need get website headers
	*
	*@param bool 
	*@return $this
	*/
	public function header($val)
	{
		curl_setopt($this -> curl, CURLOPT_HEADER, $val);
		return $this;
	}
	
	/**
	*declare curl cookies
	*
	*@param string $file (file+path)
	*@return $this
	*/
	public function cookie($file)
	{
		//path example:  dirname(__FILE__) . '/cookies.txt'
		curl_setopt($this -> curl, CURLOPT_COOKIEFILE, $file);
		curl_setopt($this -> curl, CURLOPT_COOKIEJAR, $file);
		return $this;
	}
	
	/**
	*declare false if we need get website body (html)
	*
	*@param bool 
	*@return $this
	*/
	public function noBody($val)
	{
		curl_setopt($this -> curl, CURLOPT_NOBODY, $val);
		return $this;
	}
	
	/**
	*declare user agent
	*
	*@param string $val user agent
	*@return $this
	*/
	public function agent($val)
	{
		curl_setopt($this -> curl, CURLOPT_USERAGENT, $val);
		return $this;
	}
	
	/**
	*declare http referer
	*
	*@param string $val referer
	*@return $this
	*/
	public function referer($val)
	{
		curl_setopt($this -> curl, CURLOPT_REFERER, $val);
		return $this;
	}
	
	/**
	*declare encoding type - example 'gzip'
	*
	*@param string $val
	*@return $this
	*/
	public function encoding($val)
	{
		curl_setopt($this -> curl, CURLOPT_ENCODING, $val);
		return $this;
	}
	
	/**
	*method to authorisation 
	*
	*@param string $user name
	*@param string $pass user password
	*@return $this
	*/
	public function HTTPAuth($user, $pass)
	{
		curl_setopt($this -> curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($this -> curl, CURLOPT_USERPWD, $user.':'.$pass);
		return $this;
	}	
	
	/**
	*send post data  
	*
	*@param string $toSend data to send
	*@return $this
	*/
	public function sendPost($toSend)
	{
		curl_setopt($this -> curl, CURLOPT_POSTFIELDS, $toSend );
		return $this;
	}
	
	/**
	*get result with exec
	*
	*@param bool $return true if we need return data to $variable
	*@return string
	*/
	public function get($return = true)
	{
		if(true == $return){
			curl_setopt($this -> curl, CURLOPT_RETURNTRANSFER, 1);
			return curl_exec($this -> curl);
		}
		curl_exec($this -> curl);
	}
	
	/**
	* close curl
	*/
	public function close()
	{
		curl_close($this -> curl);
	}
	
	/**
	* @throw CurlException
	*/
	public function valid()
	{
		if ( curl_errno($this -> curl) ){
   			throw new CurlException('Error #' . curl_errno($this -> curl) . ': ' . curl_error($this -> curl));
		}
	}	
			
}//curl

class CurlException extends \Exception{}
