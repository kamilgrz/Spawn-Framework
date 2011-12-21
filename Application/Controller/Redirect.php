<?php
/**
* Spawn Framework
*
* Redirect
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Controller;

class Redirect extends \Spawn\Controller
{
   
    public $url = null;

    public function indexAction()
    {     
    	$url = (null != $this -> url)? $this -> url :  '/';	
        $view = new \Spawn\View('Redirect/index');
        $view -> url = $url;
        $this -> response = $view -> render();        
    }	
    
}
