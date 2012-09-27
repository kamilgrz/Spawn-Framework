<?php
/**
* Spawn Framework
*
* Assets Loader
*
* @author Paweł Makowski
* @copyright (c) 2010-2012 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Helper
*/
namespace Spawn\View\Helper;
//use \Spawn\View\Helper\Html;
use \Spawn\Config;
use \Spawn\Data;

class AssetsLoader
{

	/**
	* 
	* @param string $name \Bin\Data file name
	*/
	public function __construct($name = 'Assets')
	{
		$this->_data = new Data($name);
		$this->_base = Config::load( 'Uri' )->get('base');
		$this->_html = new Html();
	}
	
	/**
	* 
	* @return string
	*/
	public function loadJs()
	{
		$data = $this->_data->get('js');
		$data = ($data)? $data : array();
		
		$data = $this->_addBase($data);
		
		$str = $this->_html->js($data);
		return $str;
	}
	
	/**
	* 
	* @return string
	*/
	public function loadCss()
	{
		$data = $this->_data->get('css');
		$data = ($data)? $data : array();
		
		$data = $this->_addBase($data);
		
		$str = $this->_html->css($data);
		return $str;
	}
	
	/**
	* add base path (Bin/Config/Uri)
	*
	* @param array $data
	* @return string
	*/
	protected function _addBase(array $data)
	{
		$base = $this->_base;
		$dat = array();
		foreach($data as $key){
			$dat[] = (strpos($key, 'http')!==false)? $key : $base.$key;
		}
		return $dat;
	}
		
}	