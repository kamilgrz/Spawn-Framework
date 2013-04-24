<?php
class bootstrap extends \Spawn\BootstrapAbstract
{
    protected $_use = array('database', 'session', 'event', 'firewall');

    protected function _database()
    {
        /*
        $dbc = new \Spawn\Db\Connect();
        $dbc -> connect();
        $dbc -> register();
        */
    }

    protected function _session()
    {
        /*
        $session = new \Spawn\Session();
        if(!$session -> isSecured()){
            $session = new \Spawn\Session();
        }
        $session -> register();
        */
    }

    protected function _event()
    {
    }

    protected function _firewall()
    {
    }
}
