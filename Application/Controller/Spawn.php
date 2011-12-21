<?php
/**
* Spawn Framework
*
* Default controller
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Controller;

class Spawn extends \Spawn\Controller
{

    public function indexAction()
    {     
        $view = new \Spawn\View();
        $this -> response = $view -> render();        
    }
    
    public function infoAction()
    {
    	phpinfo();
    }
}
