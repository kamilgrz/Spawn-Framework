<?php
/**
 * Spawn Framework
 *
 * firewall controller
 *
 * @author  Paweł Makowski
 * @copyright (c) 2010-2011 Paweł Makowski
 * @license http://spawnframework.com/license New BSD License
 */
namespace Controller;

use \Spawn\View as View;

class Firewall extends \Spawn\Controller
{

    /**
     * access denied
     */
    public function indexAction()
    {
        $this->response = new View('Firewall/index');
    }

    /**
     * invalid ip
     */
    public function ipAction()
    {
        $this->response = new View('Firewall/ip');
    }

    /**
     * invalid auth
     */
    public function authAction()
    {
        $this->response = new View('Firewall/auth');
    }

    /**
     * invalid acl
     */
    public function aclAction()
    {
        $this->response = new View('Firewall/acl');
    }

}
