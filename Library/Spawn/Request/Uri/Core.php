<?php
/**
* Spawn Framework
*
* uri
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Request
*/
namespace Spawn\Request\Uri;
class Core 
{     
    /**
    * @var array
    */    
    protected static $_param = array();
    
    /**
    * @var \Spawn\Confgi
    */
    public $config;
    
    /**
    * @var array
    */
    protected static $_paramName = array();
    
    /**
    * init
    */    
    public function initPath()
    {	        
        $this -> config = new \Spawn\Config('Uri');
    
		$path = $this -> _getPath();
		
		$path = $this -> _cutTheEndOfUri($path);
		
		$this -> path = $this -> _removeBasePath($path);
		
		return $this;	
    }
    
    public function initArgs($path = null)
    {
        $path = ( null == $path)? $this -> path : $path;
        $args = $this -> _createArgs($path);
		$arg=array();
		foreach($args as $key ){
			$arg[] = \Spawn\Filter::utf8($key);
		} 		
		
		self::$_param = $arg;   
		return $this;
    }
    
    /**
	*explode path , clear and return uri params
	*
	*@param string $path
	*@return array
	*/
	protected function _createArgs($path)
	{
		$args = explode('/', $path);
		if($args[0] == 'index.php' or '' == $args[0]){
			unset($args[0]);						
		}
		if($args[1] == 'index.php'){
			unset($args[1]);
		}
		$args = array_values($args);
		return $args;
	}
	
	/**
	*remove base-path with uri ($path)
	*
	*@param string $path
	*@return string
	*/
	protected function _removeBasePath($path)
	{
		$base = $this -> config -> get('base');
		if( false !== strpos($path, $base ) && strpos($path, $base ) < 2 ){ 
			$path = substr($path, strlen($base) );
		}
		
		if(false === strpos($path, '/')){
			$path = '/' . $path;
		}	
		
		return $path;
	}
	
	/**
	*remove end of uri [.html, .php etc.]
	*
	*@param string $path
	*@return string
	*/
	protected function _cutTheEndOfUri($path)
	{
		$endOfUri = $this -> config -> get('endOfUri');
		
		$path = (null != $endOfUri and strpos($path, $endOfUri) > 1 )? substr( $path, 0, strrpos( $path, $endOfUri) ) : $path;
			
		return $path;	
	}
	
	
	/**
	*check mod_rewrite config,
	*if is true return REQUEST_URI 
	*else $_GET['sf']
	*
	*@return string
	*/
	protected function _getPath()
	{			
	    if( false == $this -> config -> get('mod_rewrite') ){	
	        $_SERVER['REQUEST_URI'] = ( isset($_GET['uri']) )? $_GET['uri'] : '/';
	    }
		$path = ( isset($_SERVER['REQUEST_URI']) )?  $_SERVER['REQUEST_URI'] : '/';
		
		$qPos = strpos($path, '?');
		if(false !== $qPos){
			parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY),$_GET);
			$path = substr($path, 0, $qPos);
		}
		
		return $path;
	}
	
}
