<?php
/**
* Spawn Framework
*
* Valid
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
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
         * @param string $name
         * @param array $toValid
         * @param array $data
         * @return className
         */
	public static function factory($name, array $toValid, $data)
    {
        $cname = str_replace('_', '\\', $name);
        $className = '\Model\Valid\\' . $cname;
        if( !class_exists($className) ){
            self::createValidModelFile( $cname );
        }
        
        return new $className($toValid, $data);
    }   

    /**
     * @param string $name
     */
    public static function createValidModelFile($name)
    {    
        $dirs = explode('\\',$name);
		
		unset($dirs[ count($dirs)-1 ]);
		$dir = ROOT_PATH . 'Application' . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Valid';
		foreach($dirs as $key){
			$dir .= DIRECTORY_SEPARATOR . $key;
			if( !file_exists($dir) ){
			    mkdir($dir, 0777);
			}    
		}
		
        $cName = ( strpos($name, '\\') === false )? $name: substr($name, strripos($name, '\\') + 1); 
	    $cSpace = substr($name, 0, strripos($name, '\\') );
		$cSpace  = ($cSpace != '')? '\\'.$cSpace : '';
		
        $str = '<?php' . PHP_EOL;
        $str .= 'namespace Model\Valid' . $cSpace . ';' . PHP_EOL;
        $str .= 'class ' . $cName . ' extends \Spawn\Valid\ValidAbstract {' . PHP_EOL;
        $str .= '   protected function _init(){}' . PHP_EOL;
        $str .= '}'; 
        
        $name = str_replace( '\\', DIRECTORY_SEPARATOR, $name);
        $fileName = ROOT_PATH . 'Application' . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Valid' . DIRECTORY_SEPARATOR . $name . '.php';
        file_put_contents($fileName, $str);     
        chmod($fileName, 0777);
    }

	
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
	
	/**
	* code test
	*
	* @return array
	*/
	public function test()
	{
		$v= new \Spawn\Valid(array(
			'name'=>'Spawnm',
			'email'=>'spawnm@spawnm.pl'
		));
		$v->setRules(array(
				'name'=>array(
					'maxStrLength'=>55,
					'minStrLength'=>3,
					'required'=>true
				),
				'pass'=>array(
					'minStrLength'=>6,
					'required'=>true
				),
				'email'=>array(
					'mail'=>'',
				),
		));
		//false, array(pass)
		$res['test_1'] = array($v->validAll()->isValid(), $v->getError());
		
		$v= new \Spawn\Valid(array(
			'name'=>'damn',
			'pass'=>'superPass123',
			'date'=>'2012-01-10'
		));
		$v->setRules(array(
				'name'=>array(
					'maxStrLength'=>55,
					'minStrLength'=>3,
					'required'=>true
				),
				'pass'=>array(
					'minStrLength'=>6,
					'required'=>true
				),
				'date'=>array(
					'regex'=>'/^\d{4}-\d{1,2}-\d{1,2}$/',
				),
		));
		//true, array()
		$res['test_2'] = array($v->validAll()->isValid(), $v->getError());
		return $res;
	}

}//valid
