<?php
/**
* Spawn Framework
*
* Class to security ,token
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;

class Token
{
    /**
     * @var string
     */
    protected $_token;

    /**
     *
     * @var string
     */
    protected $_name;

    /**
     * @var Session
     */
    protected $_session;

    /**
     * @param string $name
     */
    public function __construct($name = 'token')
    {
        $this -> _session = Session::load();
        $this -> load($name);
    }

    /**
     * load token value
     *
     * @param string $name
     */
    public function load($name)
    {
        $this -> _token = $this -> _session -> get('token'.$name, null);
        $this -> _name = $name;
        if($this -> _token == null){
            $this -> setToken();
        }
        return $this;
    }

    /**
     * create new token
     *
     * @param string $name
     * @param string $val
     * @return Token
     */
    public function setToken($name = false, $val=false)
    {
        $this -> _name = ( false == $name)? $this -> _name : $name;
        $this -> _token = ($val)? $val : Math::dec2hex(time() . mt_rand(1, 25));
        $this -> _session -> set('token' . $this -> _name,  $this -> _token );
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this -> _name;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return  $this -> _token;
    }

    /**
     * @param string $data
     * @return bool
     */
    public function isValid($data)
    {
        return ($data === $this -> _token)? true : false;
    }

    /**
     * get ?url with token
     *
     * @param string $name
     * @return string
     */
    public function getUrlQuery($name = false)
    {
        $this -> _name = (false == $name)? $this -> _name : $name;
        return Url::get($this -> _name, $this -> _token);
    }
}//Token
