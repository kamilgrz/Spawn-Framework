<?php
/**
* Spawn Framework
*
* Valid
*
* @author  Paweł Makowski
* @copyright (c) 2010-2013 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Valid
*/
namespace Spawn;
class Valid extends \Spawn\Valid\Core
{
	/**
         * @var array
         */
	protected $_toValid = array();

	/**
         * how to valid
         * @var array
         */
	protected $_toValidData = array();

	/**
         * @var array
         */
	protected $_toValidError = array();

	
	/**
         *
         * @param array $arr
         * @param array $data
         */
	public function __construct(array $arr, array $data = array())
	{
		$this->initCoreFunct();
		$this -> _toValid = $arr;
		$this -> _toValidData = $data;
	}

        /**
         *
         * @param string $name
         * @param array $args
         * @return bool
         */
        public function  __call($name, array $args)
        {
            return (bool)$this -> _validatorFunct[ $name ]( $args[0], $args[1]);
        }

        /**
         *
         * @param array $arr
         * @return Valid
         */
	public function toValid(array $arr)
	{
	    $this -> _toValid = array_merge($this -> _toValid, $arr);
	    return $this;
	}
	
	/**
	* @param array $data
	* @return $this
	*/
	public function setRules(array $data)
	{
	    $this -> _toValidData = array_merge($this -> _toValidData, $data);
	    return $this;
	}

	/**
         *
         * @param string $name
         * @param string $alert
         * @return Valid
         */
	public function validParam( $name, $alert='' )
	{
		$data = $this -> _toValidData[ $name ];
		foreach($data as $key => $val){
			$isValid = ( isset($this -> _toValid[ $name ]) )? $this -> _validatorFunct[ $key ]($this -> _toValid[ $name ], $val) : false;
			if( !$isValid ){
				$this -> _toValidError[] = ($alert != '')? $alert : $name;
				break;
			}
		}
		return $this;
	}

	/**
	*valid all param in toValid
	*
	*@return $this
	*/
	public function validAll()
	{
		foreach($this -> _toValidData as $name => $data){
			$isValid = true;
			if(isset($data['required']) && !isset($this -> _toValid[ $name ])){
					$this -> _toValidError[] = $name;
					$isValid=false;
			}
			if(false !== $isValid){
				foreach($data as $key => $val){					
					if( isset($this -> _toValid[ $name ]) && $key != 'required' ){
						$isValid = $this -> _validatorFunct[ $key ]($this -> _toValid[ $name ], $val);
					}					
					if( !$isValid ){
						$this -> _toValidError[] = $name;
						break;
					}
				}
			}
		}
		return $this;
	}


	/**
	*daclare new error
	*
	*@param string $value
	*@return $this
	*/
	public function setError( $value )
	{
		$this -> _toValidError[] = $value;
		return $this;
	}

	/**
	*is valid? return true if valid() method not declare toValidError
	*
	*@return bool
	*/
	public function isValid()
	{
		return (bool)( count($this -> _toValidError) < 1 );
	}

	/**
         * @return array
         */
	public function getError()
	{
		return $this -> _toValidError;
	}
	
}//valid
