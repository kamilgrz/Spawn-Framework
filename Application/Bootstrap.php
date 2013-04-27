<?php
class bootstrap extends \Spawn\BootstrapAbstract
{
    protected $_use = array('di', 'database', 'session', 'event', 'firewall');


    protected function _di($di)
    {
        
    }
    
    protected function _database($di)
    {
        /*
        $dbc = new \Spawn\Db\Connect();
        $dbc -> connect();
        $dbc -> register();
        */
    }

    protected function _session($di)
    {
        /*
        $session = new \Spawn\Session();
        if(!$session -> isSecured()){
            $session = new \Spawn\Session();
        }
        $session -> register();
        */
    }

    protected function _event($di)
    {

    }

    protected function _firewall($di)
    {

    }
}

