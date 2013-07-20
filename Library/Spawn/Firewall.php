<?php
/**
* Spawn Framework
*
* Firewall
*
* @author  Paweł Makowski
* @copyright (c) 2013 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;

class Firewall 
{
    /**
     * @var array
     */
    protected $_data = array();

    const ALLOWED = 0;
    const ACCESS_DENIED = 1;
    const INVALID_IP = 2;
    const INVALID_AUTH = 3;
    const INVALID_ACL = 4;

    /**
     * @param string $uri
     * @param $data lambda function
     */
    public function set($uri, $data)
    {
        $this->_data[$uri] = $data;
    }

    /**
     *
     */
    public function start()
    {
        $uri = new \Spawn\Request\Uri;
        $uri = implode('/', $uri -> getAll());
        foreach($this->_data as $key => $val){
            if( preg_match('#'.$key.'#i', $uri) ){
                $result = $val();
                $this->_valid($result);
            }
        }        
    }

    /**
     * @param integer $result
     */
    protected function _valid($result)
    {
        if(self::ALLOWED != $result) {
            $this->_chooseErrorPage($result);
        }
    }

    /**
     * @param integer $result
     */
    protected function _chooseErrorPage($result)
    {
        switch($result) {
            case self::INVALID_IP: $name = 'ip'; break;
            case self::INVALID_AUTH: $name = 'auth'; break;
            case self::INVALID_ACL: $name = 'acl'; break;
            default: $name = 'index'; break;
        }
        $this->_loadPage($name);
    }

    /**
     * @param string $name
     */
    protected function _loadPage($name)
    {
        $name = $name.'Action';
        $controller = new \Controller\Firewall();
        $controller->init();
        $controller->$name();
        $controller->end();
        echo $controller -> response;
        exit;
    }

}
