<?php
/**
* Spawn Framework
*
* error 404
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Controller;

class Error extends \Spawn\Controller
{

    public function indexAction()
    {     
        $view = new \Spawn\View('Error/index');
        $this -> response = $view -> render();        
    }	

}
