<?php
/**
* Spawn Framework
*
* template controller
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Controller
*/
namespace Spawn\Controller;
class Template extends \Spawn\Controller
{
	
	/**
         * template name
         * @var string
         */
	public $tmp;

        /**
         * true if \Spawn\View\Tpl
         * @var bool
         */
	public $isTpl = false;
	
	/**
         *
         * @var \Spawn\View
         */
	public $view;

        /**
         * use cache?
         * @var bool
         */
	public $tplCache = true;
	
	/**
	* load View to $view
	*/
	public function __construct()
	{
		$this -> view = new \Spawn\View($this -> tmp);
		
		if(true == $this -> isTpl){
			$this -> view -> isTpl();
			if(true == $this->tplCache && !$this -> view -> isCache()){
			   $this -> view -> compile();
			   $this -> view -> save();
			} 
		}
	}
			
	public function init(){}
	
	/**
         * render page
         */
	public function end()
	{
		echo $this -> view -> render();
	}		
}
