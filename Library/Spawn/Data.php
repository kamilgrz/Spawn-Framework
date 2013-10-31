<?php
/**
* Spawn Framework
*
* Class to data storage
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Data
{
	/**
	* @var string
	*/
	public $path = 'Bin/Data/';
	
	/**
	* @var array
	*/
	protected $_data = array();
	

	/**
	* load data
	*
	* @param string $name
	* @return $this
	*/
	public function load($name = 'Default')
	{
        $this -> _fileName = $name;
		if( file_exists(ROOT_PATH . $this -> path . $name . '.php') && is_file(ROOT_PATH . $this -> path . $name . '.php') ){
			$this -> _data = include(ROOT_PATH . $this -> path . $name . '.php');
		}else{
			$this -> save();
		}		
		return $this;
	}
	
	public function __construct($name = 'Default')
	{
	    $this->load($name);
	}
	
	/**
	* check file isset
	*
	* @param string $name 
	* @return bool
	*/
	public function findFile($name)
	{
		return ( file_exists(ROOT_PATH . $this -> path . $name . '.php') && is_file(ROOT_PATH . $this -> path . $name . '.php') )? true : false;
	}
	
	
	/**
	* set data to array
	*
	* @param string $name
	* @param mixed $data
	* @return $this
	*/	
	public function set($name, $data)
	{
		$this -> _data[ $name ] = $data;
		return $this;
	}
		
	/**
	* return data with array
	*
	* @param string $name
	* @param mixed $or
	* @return mixed
	*/	
	public function get($name, $or = null)
	{		
		return ( isset($this -> _data[ $name ]) )? $this -> _data[ $name ] : $or;
	}
	
	/**
	* check param isset
	*
	* @param string $name 
	* @return bool
	*/
	public function findParam($name)
	{
		return ( isset($this -> _data[ $name ]) )? true : false;
	}
	
	/**
	* return all data
	*
	* @return array
	*/
	public function getAll()
	{
		return $this -> _data;
	}
	
	/**
	* delete data with array
	*
	* @param string $name
	* @return $this
	*/
	public function deleteParam($name)
	{
		unset($this -> _data[ $name ]);
		return $this;
	}
	
	/**
	* delete cache file
	*
	* @param string $name
	* @return $this
	*/
	public function deleteFile($name)
	{
		unlink(ROOT_PATH . $this -> path . $name . '.php');
		return $this;
	}
	
	/**
	* clear cached array
	*
	* @return $this
	*/
	public function deleteAll()
	{
		$this -> _data = array();
		return $this;
	}  
		
	/**
	* save array
	*
	* @param string $name
	* @param integer $life
	* @return bool
	*/	
	public function save($name = null)
	{	
		$name = ( null == $name )? $this -> _fileName : $name;
		
		$data = '<?php 
		    return '.var_export($this -> _data, true).';';
		
		$fileName = ROOT_PATH . $this -> path . $name . '.php';
		file_put_contents($fileName, $data);
		chmod($fileName, 0777);
		
		return $this;
	}
		
}//Data
