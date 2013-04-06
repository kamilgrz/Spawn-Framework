<?php
/**
* Spawn Framework
*
* Menu
*
* @author Paweł Makowski
* @copyright (c) 2013 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Helper
*/
namespace Spawn\View\Helper;

class Menu
{
	/**
	* @var array
	*/
	protected $_rows = array();
	
	/**
	* @var string
	*/
	protected $_tpl = 'default';
	
	/**
	* @param string $action 
	*/
	public function __construct($action = null)
	{
		$this->_init();
		if(null != $action){
			$name = '_'.$action;
			$this->$name();
		}
	}
	
	/**
	* @param string $key
	* @param array $val
	* @return self
	*/
	public function setRow($key, array $val)
	{
		$this->_rows[$key] = $val;
		return $this;
	}
	
	/**
	* @return array
	*/
	public function getRows()
	{
		$rows = array();
		foreach($this->_rows as $key => $val){
			if(!isset($val['href'])){
				$val['href'] = $val[0];
			}
			if(!isset($val['name'])){
				$val['name'] = $val['href'];
			}
			if(!isset($val['title'])){
				$val['title'] = $val['name'];
			}
			$rows[$key] = $val;
		}
		return $rows;
	}
	
	/**
	* @return string
	*/
	public function render()
	{
		$uri = new \Spawn\Request\Uri;	
		$view = new \Spawn\View('Menu'.DIRECTORY_SEPARATOR.$this->_tpl);
		$view->uri = implode('/', $uri -> getAll());
		$view->rows = $this->getRows();
		return $view->render();
	}
	
	/**
	*
	*/
	protected function _init()
	{
	}	
	
}	
