<?php
/**
* Spawn Framework
*
* Class to benchmark
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Benchmark 
{
	
	/**
	* array with debug informations
	*
	* @var array
	*/
	protected $_test = array(); 
	
	/**
	* @var string
	*/
	public $path = 'Bin/Debug/';
   
   	/**
	*start benchmark
	*
	*@param string $name
	*/
	public function start($name = 'Default')
	{
		$this -> _test[ $name ]['memory'] = memory_get_usage();
		$this -> _test[ $name ]['time'] = microtime(true);
   	}
   	
   	/**
	*stop benchmark
	*
	*@param string $name
	*/
	public function stop($name = 'Default', $dec = 5)
	{
   		$this -> _test[ $name ]['memory'] = memory_get_usage() - $this -> _test[ $name ]['memory'];
   		$this -> _test[ $name ]['time'] = number_format(microtime(true) - $this -> _test[ $name ]['time'], $dec);
	}
	
	/**
	*get info
	*
	*@param string $name
	*@return array array
	*/
	public function get($name = 'Default')
	{
		$rn = $this -> _test[ $name ];
		unset($this -> _test[ $name ]);
		return $rn;
	} 
	
	/**
	* @return array
	*/
	public function getAll()
	{
	    $rn = $this -> _test;
	    $this -> _test = array();
		return $rn;
	}
	
	/**
	* encode to json and save test array to file 
	* file name is controller nad action name
	*/
	public function save()
	{
	    $uri = new Request\Uri;
		//create file name
		$fileName= $uri -> param(0) . '_' . $uri -> param(1) . '.json';
		
		//create array to save
		$b2j=array();
		foreach($this -> _test as $key => $val){
			$b2j['items'][] = array('name' => $key, 'time' => $val['time'], 'memory' => $val['memory']);
		}
		
		//encode to json and save
		$bSave = json_encode($b2j);		
		file_put_contents(ROOT_PATH . $this->path . $fileName, $bSave);		
	}
	
	/**
	*decode json and return  array assoc
	*
	*@param string $controller name | controller and action - if null - load actual use controller
	*@param string $action name - if null - load actual use action
	*@return array
	*/
	public function load($controller = null, $action = null)
	{
	    if(null == $controller or null == $action){
	        $uri = new Request\Uri;		
		    $data = $uri -> param(0) . '_' . $uri -> param(1);
		}else{
		    $data = $controller . '_' . $action;
		}
		$fileName = $data.'.json';
		
		//load file and decode
		$data=file_get_contents(ROOT_PATH . $this->path . $fileName);
		return json_decode($data);
	}
	
}//benchmark
