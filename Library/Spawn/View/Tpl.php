<?php
/**
* Spawn Framework
*
* view template
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package View
*/
namespace Spawn\View;
class Tpl
{
	/**
	* path to template files (width tags)
	* @var string
	*/
	public $sourcePath = 'Application/View/Tpl/'; 
	
	/**
	* path to compiled files (phtml)
	* @var string
	*/
	public $compilePath = 'Application/View/Tpl_c/';
	
	/**
	* tpl to compile
	* @var string
	*/
	protected $_tpl = null;
	
	/**
	* compiled tpl
	* @var string
	*/
	protected $_compiled = null;

	/**
	*declate template file name or data to compile
	*
	*@param string $name
	*@param bool $isData
	*/
	public function __construct($str, $isData = false)
	{					
	    $this -> _data = new \Spawn\Data('Tpl');
		if(false == $isData){	
			$this -> _name = $str;	
		}else{
			$this -> _tpl = $str;
		}				
	}
	
	/**
	* @param string $name
	* @return $this
	*/
	public function setName($name)
	{
	    $this -> _name = $name;
	    return $this;
	}
		
	/**
	*declare new path to tpl files
	*
	*@param string $dir
	*@return $this
	*/	
	public function setSourcePath($path)
	{
		$this -> sourcePath = $path; 
		return $this;
	}
	
	/**
	*declare new path to compile files
	*
	*@param string $dir
	*@return $this
	*/	
	public function setCompilePath($path)
	{
		$this -> compilePath = $path; 
		return $this;
	}
	
	/**
	*return path to template files (width tags)
	*
	*@return string
	*/
	public function getSourcePath()
	{
		return $this -> sourcePath;
	}
	
	/**
	*return path to compiled files (php)
	*
	*@return string
	*/
	public function getCompilePath()
	{
		return $this -> compilePath;
	}
			
	/**
	*checks compiled file exists
	*
	*@return bool
	*/			
	public function isCache()
	{
		return (bool)( file_exists(ROOT_PATH . $this -> compilePath . $this -> _name . '.phtml') );		
	}
	
	/**
	*set new template tag
	*
	*@param string $key 
	*@param string $val
	*@return $this
	*/
	public function setTag($key, $val)
	{
		$this -> _data -> set($key, $val);
		return $this;
	}
		
	/**
	*compile template
	*
	*@return $this
	*/
	public function compile()
	{
		$tag = $this -> _data -> getAll();
		
		if(null == $this -> _tpl){
			$this -> loadTpl();
		}	
		
		$toCompile = $this -> _tpl;
		
		foreach($tag as $key => $val){
			$toCompile = preg_replace('#'.$key.'#', $val, $toCompile);
		}
		$this -> _compiled = $toCompile;
				
		return $this;
	}
	
	/**
	*save to file compiled tpl
	*
	*@param string $name
	*@result $this
	*/
	public function save($name = null)
	{
		$this -> _name = (null != $name)? $name : $this -> _name;
		file_put_contents(ROOT_PATH . $this -> compilePath . $this -> _name . '.phtml', $this -> _compiled );
		return $this;
	}
	
	public function saveTags()
	{
	    $this -> _data -> save();
	    return $this;
	}
	
	/**
	*return tpl
	*
	*@return string
	*/
	public function getTpl()
	{
		return $this -> _tpl;
	}
	
	/**
	*set tpl to compile
	*
	*@param string $tpl
	*@return $this
	*/
	public function setTpl($tpl)
	{
		$this -> _tpl = $tpl;
		return $this;
	}
	
	/**
	*set compiled tpl to save
	*
	*@param string $tpl
	*@return $this
	*/
	public function setCompiledTpl($tpl)
	{
		$this -> _compiled = $tpl;
	}
	
	/**
	*get compiled tpl
	*
	*@return string
	*/
	public function result()
	{
		return $this -> _compiled;
	}
	
	/**
	*return compiled tpl
	*
	*@return string
	*/
	public function __toString()
	{
		return $this -> _compiled;
	}
	
	/**
	*load/declatre data to compile
	*
	*@param string $data
	*@return $this
	*/
	public function loadTpl($data = null)
	{
		$this -> _tpl = (null != $data)? $data : file_get_contents(ROOT_PATH . $this -> sourcePath . $this -> _name . '.tpl');
		return $this;
	}
	
}//tpl
