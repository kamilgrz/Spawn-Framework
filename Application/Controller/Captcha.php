<?php
/**
* Spawn Framework
*
* Captcha
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Controller;

class Captcha extends \Spawn\Controller
{	
	public function indexAction()
	{
		$name = $this -> request->uri -> param(2, 'default');	
		$img = new \Spawn\Captcha( new \Spawn\Session );
		$img -> render($name);
	}
}
