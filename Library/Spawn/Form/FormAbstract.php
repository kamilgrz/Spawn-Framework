<?php
/**
* Spawn Framework
*
* Class to generate form 
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Form
*/
namespace Spawn\Form;

abstract class FormAbstract extends \Spawn\Form
{
	/**
	* input configs - to sf_form::create
	*
	* @var array
	*/
	protected $_input = array();

        /**
         *
         * @var string
         */
	protected $_lastInputName;

        /**
         *
         * @var array
         */
	protected $_addValidData = array();
	
        /**
         *
         * @var array
         */
	protected $_addValid = array();
	
	/**
	* @var bool
	*/
        protected $_isInit = false;
        
        /**
        *
	* @var string
	*/
        protected $_validator = null;
        
        /**
        * <form> params
        *
	* @var array
	*/
        protected $_param = array();
        
        /**
	* @var string
	*/
        protected $_action = '';
        
        /**
	* @var string
	*/
        protected $_method = 'post';
        
        /**
	* @var string
	*/
        protected $_fieldset = '';
        
        /**
	* @var string
	*/
        protected $_legend = '';
        
        /**
	* set validator class name
	*
	* @param string $name
	* @return $this
	*/
        public function setValidator($name)
        {
        	$this -> _validator = $name;
        	return $this;
        }

	/**
	* set action
	*
	* @param string $data
	* @return $this
	*/
	protected function action($data)
	{
		$this -> _action = $data;
		return $this;
	}
	
	/**
	* set method
	*
	* @param string $data
	* @return $this
	*/
	protected function method($data)
	{
		$this -> _method = $data;
		return $this;
	}
	
	/**
	* set <form> param
	*
	* @param string $name
	* @param string $value
	* @return $this
	*/
	protected function param($name, $value)
	{
		$this -> _param[$name] = $value;
		return $this;
	}
	
	/**
	* set legend
	*
	* @param string $val
	* @param array $params
	* @return $this
	*/
	public function setLegend($val, $params=null)
	{
		$this -> _legend = $this -> legend($val, $params);
		return $this;
	}
	
	/**
	* set fieldset
	*
	* @param array $params
	* @return $this
	*/
	public function setFieldset($params = null)
	{
		$this -> _fieldset = $this -> fieldset($params);
		return $this;
	}	
	
	/**
	* declare config 
	*
	* @param string $name
	* @return $this
	*/
	protected function useData($name = 'FormAbstract')
	{
		$data = new \Spawn\Data($name);
		$this -> _data = $data -> getAll();
		return $this;
	}
	
	/**
	* set new config input
	*
	* @param string $label
	* @param string $name
	* @param string $value
	* @return $this
	*/
	protected function setDataInput($label, $name, $value = null)
	{
		if( !isset($this -> _data[$name]) ){
			throw new FormAbstractException($name.' not found!');
		}
		$this -> _lastInputName = $name;
		$this -> _input[ $label ] = $this -> _data[$name];
                $value = (null != $value)? $value :
                    (( isset($this -> _input[ $label ]['value']) )?
                            $this -> _input[ $label ]['value'] : null);
		$this -> _input[ $label ]['value'] = $value;
		return $this;
	}	
	
	public function addValid($val = null)
	{
	    if(null == $val){
	        $this -> _addValidData[] = $this -> _lastInputName; 
	    }else if( is_string($val) ){
	        $this -> _addValidData[] = $val; 
	    }else{
	        $this -> _addValid[ $this -> _lastInputName ] = $val;
	    }
	    return $this;
	}
	
	/**
	* set new input
	*
	* @param string $label
	* @param array $input
	* @return $this
	*/
	protected function setInput($label, array $input)
	{
	    $this -> _lastInputName = $input['name'];
		$this -> _input[ $label ] = $input;
		return $this;
	}	
	
	/**
	* set new value to input
	*
	* @param array $data
	* @return $this
	*/
	protected function setInputs(array $data)	
	{
	    $arr = end($data);
	    $this -> _lastInputName = $arr['name'];
		$this -> _input = array_merge($this -> _input, $data);
		return $this;
	}	
	
	/**
	* use model_valid_* 
	*
	* @param array $toValid
	* @param mixed $data
	* @return bool
	*/
	public function isValid(array $toValid, $data = null)
	{		
        $this -> init();
	    $className = $this -> _validator;
		
        if( null == $className){              
            $className = substr(get_class( $this ), mb_strlen('Model\Form\\', 'utf-8'));                             
        }
           
        $valid = \Spawn\Valid::factory($className ,$toValid, $data);
        $valid -> setValidNames( $this -> _addValidData );
        $valid -> setValid( $this -> _addValid );
        
		$this -> toError( $valid -> getValid() -> getError() );
		
		return $valid -> isValid();
	}

	/**
	* get array of error
	*
	* @return array
	*/
        public function getError()
        {
            return $this -> _toErrorArray;
        }

	/**
	* init form _init() 
	*
	* @param mixed $args
	* @return $this
	*/
	public function init($args = null)
        {
            if( false == $this -> _isInit ){
                $this -> _isInit = true;
                $this -> _init($args);
            }
            return $this;
        }
	
	/**
	* render inputs
	*
	* @return string
	*/
	public function render($form = true)
	{
		$this -> init();
		$str = '';
		
		if(true == $form){
		    $str .= $this -> open( $this -> _action, $this -> _method, $this -> _param );
		    $str .= $this -> _fieldset;
		    $str .= $this -> _legend;
		    $str .= $this -> create($this -> _input);
		    $str .= $this -> close();
		}else{
		     $str .= $this -> create($this -> _input);
		}
		
		return $str;
	}
	
	/**
	* render form
	*
	* @return string
	*/
	public function __toString()
	{
		try{
			$data = $this -> render();
		}catch(FormAbstractException $e){
		    $data = $e -> getMessage();
		}catch(\Exception $e){
			$data = $e -> getMessage();
		}
		return $data;
	}
}

class FormAbstractException extends \Exception {} 
