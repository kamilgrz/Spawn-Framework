<?php
/**
* Spawn Framework
*
* Class to security ,brute force detector
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Bfa
{
	/**
	* number of attempts to block
	* @var integer
	*/
	public $errorLimit = 5;

	/**
	* block for x seconds
	* @var integer
	*/
	public $timeLimit = 900;

	/**
	* @var array
	*/
	protected $_data;

	/**
	* @var Data
	*/
	protected $_dataStorage;

        /**
         * declare config
         * @param int $errorLimit
         * @param int $timeLimit
         * @param int $dir
         * @param int $file
         */
	public function __construct($file = 'Bfa' )
	{
	    $req = new Request;
		$this -> _ip = $req -> ip();

		$this -> _dataStorage = new Data($file);

		$this -> _data = $this -> _dataStorage -> get($this -> _ip);
		$this -> clean();
	}

	/**
	* return number of errors
	*
	* @return integer
	*/
	public function getErrorLevel()
	{
		$x =( is_array($this -> _data) )? $this -> _data['errors']: 0;
		return $x;
	}

	/**
	* return last error time + timeLimit
	*
	* @return integer
	*/
	public function getTime()
	{
		return ( is_array($this -> _data) )? $this -> _data['time']: 0;
	}

	/**
	* clean user data if last error time + timeLimit < time()
	*
	* @return $this
	*/
	public function clean()
	{
		if( is_array($this -> _data) ){
			if($this -> _data['time'] < time() ){
				$this -> _dataStorage -> deleteParam($this -> _ip);
				$this -> _data = null;
			}
		}
		return $this;
	}

	/**
	* clean dataStoraged array with died rows
	*
	* @return $this
	*/
	public function cleanAll()
	{
		$data = $this -> _dataStorage -> getAll();
		foreach( $data as $key => $val)
		{
			if( $val['time'] < time() ){
				$this -> _dataStorage -> deleteParam($key);
			}
		}
		$this -> _dataStorage -> save();
		return $this;
	}

	/**
	* declare valid result
	*
	* @param $val
	* @return $this
	*/
	public function setValid($val)
	{
		$this -> _isValid = (bool) $val;
		$this -> _botErrorLogManager();
		return $this;
	}

	/**
	* manages user error
	*/
	protected function _botErrorLogManager()
	{
		if(false === $this -> _isValid) {
			$this -> _data['errors'] = ($this -> getErrorLevel() > 0)? $this -> getErrorLevel()+1 : 1;
			$this -> _data['time'] = time() + $this -> timeLimit;
			$this -> _dataStorage -> set($this -> _ip, $this -> _data) -> save();
		} else {
			$this -> _dataStorage -> deleteParam($this -> _ip) -> save();
		}
	}

	/**
	* detect brute force
	* true - we have atack
	* false - clean :)
	*
	* @return bool
	*/
	public function isBot()
	{
		return (bool)( $this -> getErrorLevel() >= $this -> errorLimit );
	}

}//bfa
