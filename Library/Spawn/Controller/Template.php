<?php
/**
* Spawn Framework
*
* template controller
*
* @author  Paweł Makowski
* @copyright (c) 2010-2012 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Controller
*/
namespace Spawn\Controller;
use \Spawn\View;

class Template extends \Spawn\Controller
{
	
	/**
         * template name
         * @var string
         */
	public $tmp = null;
	
	/**
	* false or 'content' View object
	* @var bool/object
	*/
	public $content = true;

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
	* load View to $view and $view->content
	*/
	public function __construct()
	{
	    parent::__construct();
	     
		if(null === $this -> tmp) {
			$uri = new \Spawn\Request\Uri;
			$this -> tmp = ucfirst($uri->param(0));
		} 
		$this -> view = new View($this -> tmp);		
		$this -> view = $this -> _tpl($this -> view);
		
		if( true === $this -> content) {
			$this -> view -> content = new View();		
			$this -> view -> content = $this -> _tpl($this -> view -> content);
		}
	}
	
	/**
	* load and compile template
	*
	* @var View
	* @return View
	*/
	private function _tpl($view)
	{
		if(true === $this -> isTpl){
				$view -> isTpl();
				if( true == $this -> tplCache && !$view -> isCache() ){
					$view -> compile();
					$view -> save();
				} 
		}
		return $view;
	}
			
	/**
	*
	*/	
	public function init(){}
	
	/**
         * render page
         */
	public function end()
	{
		echo $this -> view -> render();
	}		
}

