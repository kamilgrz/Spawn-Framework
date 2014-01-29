<?php
/**
* Spawn Framework
*
* Router
*
* @author  Paweł Makowski
* @copyright (c) 2010-2012 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Router
{
	/**
	* @var array
	*/
	protected $_val;	
		
	/**
	* @var bool
	*/
	protected $_isRoute = false;
	
	/**
	* @var \Spawn\Uri
	*/
	protected $_uri;
	
	/**
	* @var \Spawn\Config
	*/
	protected $_config;
	
	/**
         * init routing
         * @param Request\Uri $uri
         */
	public function init(Request\Uri $uri)
	{
	    $this -> _uri = $uri;
		$this -> _config = new Config('Router');
				
		$this -> _staticRoute();
		if(false == $this -> _isRoute){		
		    $this -> _callRoute();
		}
		
		if(false == $this -> _isRoute){
		    $this -> _dynamicRoute();
		}
		
		if(true == $this -> _isRoute){ 
		    $this -> _uri -> initArgs();
		}
				
		$this -> _createParam()
		      -> _createParamName();
		      
		$this -> _config -> clear();
	}
	
	protected function _callRoute()
	{
		$cfgArr = $this -> _config -> get('Call');
		$req = $this -> _uri -> path;
		foreach($cfgArr as $key){
			$this -> _isRoute = (bool) $this -> _val = $key($req);
			if(true == $this -> _isRoute) {
				$this -> _location();
				$this -> _uri -> path = preg_replace('#'.$this -> _uri -> param(0).'#i', $this -> _val['request_uri'], $this -> _uri -> path);
				break;
			}
		}
	}
	
	protected function _staticRoute()
	{
	    $cfgArr = $this -> _config -> get('Static');
	    $act = $this -> _uri -> param(0);
	    if( isset( $cfgArr[ $act ] ) ){
	        $this -> _isRoute = true;
	        $cfgArr[ $act ] = ( !is_callable($cfgArr[ $act ]) )? $cfgArr[ $act ] : $cfgArr[ $act ]($this -> _uri -> path);																			
			$this -> _val = $cfgArr[ $act ];			
			$this -> _location();			
			$this -> _uri -> path = $cfgArr[ $act ]['request_uri'];			
	    }
	}
	
	protected function _dynamicRoute()
	{
	    $cfgArr = $this -> _config -> get('Dynamic');
	    $req = $this -> _uri -> path;
		foreach($cfgArr as $key => $val)
		{
			if( preg_match('#'.$key.'#i', $req) ){
				$this -> _isRoute = true;
				$val = ( !is_callable($val) )? $val : $val($req);					
				$this -> _val = $val;
				$this -> _location();
				$this -> _uri -> path = preg_replace('#'.$key.'#i', $val['request_uri'], $req);
				      			
				break;
			}
		}
	}
	
	/** 
	* @return bool
	*/
	public function isRoute()
	{
		return $this -> _isRoute;
	}
			
	/**
	* redirect if we have location param
	*/
	protected function _location()
	{
		if( isset($this -> _val['location']) ){
			header('location: '.$this -> _val['location']);
			exit;
		}
	}
	
	/**
	* create params if we have 'param' array in config
	* if param is exists we dont replace it
	*/
	protected function _createParam()
	{
		if( isset($this -> _val['param']) AND is_array($this -> _val['param']) ){
			foreach($this -> _val['param'] as $key => $val){
				if( $this -> _uri -> param($key, null) != null){
				    $this -> _uri -> setParam($key, $val);
				}    
			}
		}		
		return $this;
	}
	
	/**
	* create param names
	*/
	protected function _createParamName()
	{
		if( isset($this -> _val['paramName']) AND is_array($this -> _val['paramName']) ){
			foreach($this -> _val['paramName'] as $key => $val){
				$this -> _uri -> setParamName($key, $val);
			}
		}
		return $this;
	}	
	
	/**
         *
         * @return Request\Uri
         */
	public function getUri()
	{
	    return $this -> _uri;
	}
	
	/** 
	* @return string 
	*/
	public function getController()
	{
		return $this -> _uri -> param(0);
	}
	
	/** 
	* @return string 
	*/
	public function getAction()
	{
		return $this -> _uri -> param(1);
	}
	
}//router
