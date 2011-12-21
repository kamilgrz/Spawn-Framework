<?php
/**
* Spawn Framework
*
* abstract validator
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Valid
*/
namespace Spawn\Valid;

abstract class ValidAbstract extends \Spawn\Valid
{	
	const VALID_ALL   = 1;
	const VALID_ASSOC = 2;
	
	/**
	* @var integer
	*/
	protected $_validType;
	
	/**
	* @var array
	*/
	protected $_toValidDataParamName = array();
	
	/**
	* @var array
	*/
	protected $_toValidDataFileName = 'ValidAbstract';
	
	/**
	* @var array
	*/
	protected $_data = array();
	
	/**
	* @var array
	*/
	protected $_alert = array();

        /**
         *
         * @param array $toValid
         * @param mixed $data
         */
	public function __construct(array $toValid, $data = null){
	    $this -> initCoreFunct();
		$this -> _toValid = $toValid;
		$this -> _init($data);
		$this -> _loadData();
		
	}

        /**
         *
         * @param array $data
         * @return $this
         */
	public function setDataToValid(array $data)
	{
		$this -> _toValid = array_merge($this -> _toValid, $data);
		return $this;
	}
	
	/**
	*
	* @param string $name
	* @return $this
	*/
	public function setValidName($name)
	{
		$this -> _toValidDataParamName[] = $name;		
		return $this;		
	}
	
	/**
	*
	* @param array $name
	* @return $this
	*/
	public function setValidNames(array $name)
	{
		foreach($name as $key){
			$this -> _toValidDataParamName[] = $key;
		}
		
		return $this;		
	}
	
	/**
	* file name
	* @param string $name
	* @return $this
	*/
	public function setDataName($name)
	{
		$this -> _toValidDataFileName = $name;
		return $this;
	}
	
	/**
	* to valid data
	* @param string $name
	* @return $this
	*/
	public function setValid(array $data)
	{
		$this -> _toValidData = array_merge($this -> _toValidData, $data);
		return $this;
	}
    
    /**
	*
	*/	
	protected function _loadData()
	{
		$data = new \Spawn\Data( $this -> _toValidDataFileName );
		$this  ->  _data = $data  ->  getAll();
	}
	
	/**
	* @throw ValidAbstractException
	*/
	protected function  _loadDataParam()
	{
		foreach($this -> _toValidDataParamName as $key){
		    if( !isset($this -> _data[$key]) ) throw new ValidAbstractException('Key "'.$key.'" not found in valid datafile!');
			$this -> _toValidData[$key] = $this -> _data[$key];
		}	
	}
	
	/**
	*
	* @param string $name
	* @param string $alert
	* @return $this
	*/
	public function setAlert($name, $alert)
	{
		$this -> _alert[$name] = $alert;
		return $this;
	}
	
	/**
	*
	* @param string $validType
	* @return $this
	*/
	public function getValid($validType = null)
	{
	    $this -> _loadDataParam();
		if(null != $validType){
			$this -> _validType = $validType;
		}
		if( $this -> _validType !== self::VALID_ASSOC ){
			$this -> validAll();
		}else{
			foreach($this -> _toValidData as $name => $val){
			    if( !isset($this -> _alert[ $name ]) ) throw new ValidAbstractException('Alert "'.$name.'" not found!');
				$this -> validParam($name, $this -> _alert[ $name ]);
			}
		}
		
		return $this;
	}	
	
}

class ValidAbstractException extends \Exception {}
