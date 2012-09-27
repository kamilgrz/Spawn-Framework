<?php
/**
* Spawn Framework
*
* valid core functions
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Valid
*/
namespace Spawn\Valid;
class Core
{
    /**
     *
     * @var array
     */
    protected $_validatorFunct = array();

        /**
         *
         * @return $this
         */
	public function initCoreFunct()
	{
	    $this -> _validatorFunct = array(
	            'ip' => function($val, $options = null) {
		                return (bool)filter_var( $val , FILTER_VALIDATE_IP, $options );
	                },
	            'mail' => function($val, $options = null) {
		                return (bool)filter_var( $val,  FILTER_VALIDATE_EMAIL, $options );
	                },
                'url' => function($val, $options = null) {
		                return (bool)filter_var( $val, FILTER_VALIDATE_URL, $options );
	                },
                'maxStrLength' => function($str, $max) {
		                return ( is_string($str) AND mb_strlen(trim($str), 'utf-8') <= $max );
	                },
                'minStrLength' => function($str, $min) {
		                return ( is_string($str) AND mb_strlen(trim($str), 'utf-8') >= $min );
	                },
	            'max' => function($val, $max) {
	                    return ( (int)$val <= $max );
	                },
	            'min' => function($val, $min) {
	                    return ( (int)$val >= $min );
	                },
				'simile' => function($str, $str2) {
	                    return ( $str === $str2 );
	                },   
				'in' => function($val, array $arr) {
	                    return ( in_array($val, $arr) );
	                }, 
				'captcha' => function($val, $name = 'default') {
	                    $captcha = new \Spawn\Captcha();
						if($captcha->isDeclared($name)){
							$x= $captcha->isValid($val, $name);
							echo (int)$x;
							return $x;
						}
						return false;
	                }, 	
				'unique' => function($val, array $dbInfo) {
	                    $i = \Spawn\Orm::factory($dbInfo[0])->where($dbInfo[1], $val)->count();
						if($i>0){
							return false;
						}
						return true;
	                },		
                'regex' => function($str, $reg) {
		                if( preg_match($reg, $str) ){
			                return true;
		                }
		                return false;
	                }	    
	            );
        return $this;
    }

    /**
     *
     * @param string $name
     * @param lambda $validatorFunct
     * @return $this
     */
    public function setFunct($name, $validatorFunct)
    {
        $this -> _validatorFunct[ $name ] = $validatorFunct;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getAll()
    {
        return $this -> _validatorFunct;
    }

    /**
     *
     * @param string $name
     * @return $this
     */
    public function deleteFunct($name)
    {
        if( isset($this -> _validatorFunct[ $name ]) ){
            unset($this -> _validatorFunct[ $name ]);
        }
        return $this;    
    }

}
