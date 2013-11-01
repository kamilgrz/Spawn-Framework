<?php
/**
* Spawn Framework
*
* Controller
*
* @author  Paweł Makowski
* @copyright (c) 2010-2013 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Controller
*/
namespace Spawn;
class Controller
{	
    
    /**
    * @var Loader
    */
    public $loader;
    
    /**
    * @var Request
    */
    public $request;
    
    /**
    * @var Event
    */
    public $event; 
    
    /**
    * @var string
    */
    public $response = '';

    /**
     * @var \Spawn\DI
     */
    public $di;

    /**
     *
     */
    public function __construct($di=null)
    {
        $this->di = (null != $di)? $di : new DI;
        if(!$this->di->has('alert')) $this->di->set('alert', '\Spawn\View\Helper\Alert');
        if(!$this->di->has('auth')) $this->di->set('auth', '\Spawn\Auth');
        if(!$this->di->has('benchmark')) $this->di->set('benchmark', '\Spawn\Benchmark');
        if(!$this->di->has('token')) $this->di->set('token', '\Spawn\Token');
        if(!$this->di->has('acl')) $this->di->set('acl', '\Spawn\Acl');
    }

    /**
     * @param $name
     * @return object
     */
    function __get($name)
    {
        $model = $this->di->get($name);
        return $model;
    }

    /**
    *
    * @param string $url
    */
	public function redirect($url)
	{
		$this -> event -> run('Spawn.Redirect');
		header('location: ' . $url); 
		$redirect = new \Controller\Redirect();
		$redirect -> url = $url;
		$redirect -> indexAction();
		echo $redirect -> response;
		exit;
	}
	
	/**
	* render 404 page
	*
	* @param string $path
	*/
	public function error404()
	{
		Spawn::error404();
	}
	
	/**
	* refresh location
	*
	* @param string $url
	* @param integer $time
	*/
	public function refresh($url, $time = 0){
	    $this -> event -> run('Spawn.Redirect');
		header('Refresh: ' . $time . '; url=' . $url); 
		$redirect = new \Controller\Redirect();
		$redirect -> url = $url;
		$redirect -> indexAction();
		echo $redirect -> response;
		exit;
	}
	
	/**
	* get controller action
	*
	* @param string $controller
	* @param string $action
	* @param mixed $param
	* @param bool $init
	* @param bool $end
	*/
	public function getAction($controller, $action, $param=null, $init = true, $end = true){
	    
		$cName = '\Controller\\' . $controller;
		$aName = $action . 'Action';
		
		if(!class_exists($cName)) return false;
		if(!method_exists($cName, $aName)) return false;
		
		$this -> request -> uri -> paramReplace(0, $controller);
		$this -> request -> uri -> paramReplace(1, $action);
		
		$param = ($param == null)? $this -> request : $param;
		
		$this -> event -> run('Spawn.GetAction', array($controller, $action, $param, $init, $end));
		$controller = new $cName;
		$controller -> loader = $this -> loader;
		$controller -> request = $param;
		$controller -> event = $this -> event;
		if($init == true) $controller -> init();
		$controller -> $aName($param);
		if($end == true) $controller -> end();
	}
	
	public function init(){}
	
	public function end(){}
	
}//controller
