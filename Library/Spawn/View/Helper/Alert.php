<?php
/**
 * Spawn Framework
 *
 * Alert
 *
 * @author  Paweł Makowski
 * @copyright (c) 2013 Paweł Makowski
 * @license http://spawnframework.com/license New BSD License
 * @package Helper
 */

namespace Spawn\View\Helper;

class Alert
{
    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @var string
     */
    protected $_tplBlock = '<div class="alert alert-block {ALERT}"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>{TITLE}</h4>{BODY}</div>';

    /**
     * @var string
     */
    protected $_tpl = '<div class="alert {ALERT}"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>{TITLE}</strong> {BODY}</div>';

    /**
     *
     */
    public function __construct()
    {
        $this->_registry = new \Spawn\Registry('Sf.Alert');
    }

    /**
     * @param mixed $title
     * @param mixed $val
     * @return $this
     */
    public function setError($str, $val = null)
    {
        $title = (null == $val)? '' : $str;
        $val = (null == $val)? $str : $val;

        $this->_registry->set('error.title', $title);
        $this->_registry->set('error', $val);

        return $this;
    }

    /**
     * @param mixed $title
     * @param mixed $val
     * @return $this
     */
    public function setInfo($str, $val = null)
    {
        $title = (null == $val)? '' : $str;
        $val = (null == $val)? $str : $val;

        $this->_registry->set('info.title', $title);
        $this->_registry->set('info', $val);

        return $this;
    }

    /**
     * @param mixed $title
     * @param mixed $val
     * @return $this
     */
    public function set($str, $val = null)
    {
        $title = (null == $val)? '' : $str;
        $val = (null == $val)? $str : $val;

        $this->_registry->set('alert.title', $title);
        $this->_registry->set('alert', $val);

        return $this;
    }

    /**
     * @param mixed $title
     * @param mixed $val
     * @return $this
     */
    public function setSuccess($str, $val = null)
    {
        $title = (null == $val)? '' : $str;
        $val = (null == $val)? $str : $val;

        $this->_registry->set('success.title', $title);
        $this->_registry->set('success', $val);

        return $this;
    }

    /**
     * @param string $alert
     * @param string $body
     * @return string
     */
    protected function getTpl($alert, $title, $body, $isBlock = false)
    {
        $tpl = ($isBlock)? $this->_tplBlock :  $this->_tpl;
        return str_replace(array('{ALERT}', '{TITLE}', '{BODY}'), array($alert, $title, $body), $tpl);
    }


    /**
     * @param bool $isBlock
     * @return string
     */
    public function getSuccess($isBlock = false)
    {
        $title = $this->_registry->get('success.title');
        $data = $this->_registry->get('success');
        if($data == null) return '';
        $data = $this->_prepareData($data);
        return $this->getTpl('alert-success', $title, $data, $isBlock);
    }

    /**
     * @param bool $isBlock
     * @return string
     */
    public function getInfo($isBlock = false)
    {
        $title = $this->_registry->get('info.title');
        $data = $this->_registry->get('info');
        if($data == null) return '';
        $data = $this->_prepareData($data);
        return $this->getTpl('alert-info', $title, $data, $isBlock);
    }

    /**
     * @param bool $isBlock
     * @return string
     */
    public function getError($isBlock = false)
    {
        $title = $this->_registry->get('error.title');
        $data = $this->_registry->get('error');
        if($data == null) return '';
        $data = $this->_prepareData($data);
        return $this->getTpl('alert-error', $title, $data, $isBlock);
    }

    /**
     * @param bool $isBlock
     * @return string
     */
    public function get($isBlock = false)
    {
        $title = $this->_registry->get('alert.title');
        $data = $this->_registry->get('alert');
        if($data == null) return '';
        $data = $this->_prepareData($data);
        return $this->getTpl('', $title, $data, $isBlock);
    }

    /**
     * @param string $str
     * @param mixed $val
     * @param bool $isBlock
     * @return string
     */
    public function error($str, $val = null, $isBlock = false)
    {
        $title = (null == $val)? '' : $str;
        $data = (null == $val)? $str : $val;
        $data = $this->_prepareData($data);
        return $this->getTpl('alert-error', $title, $data, $isBlock);
    }

    /**
     * @param string $str
     * @param mixed $val
     * @param bool $isBlock
     * @return string
     */
    public function success($str, $val = null, $isBlock = false)
    {
        $title = (null == $val)? '' : $str;
        $data = (null == $val)? $str : $val;
        $data = $this->_prepareData($data);
        return $this->getTpl('alert-success', $title, $data, $isBlock);
    }

    /**
     * @param string $str
     * @param mixed $val
     * @param bool $isBlock
     * @return string
     */
    public function info($str, $val = null, $isBlock = false)
    {
        $title = (null == $val)? '' : $str;
        $data = (null == $val)? $str : $val;
        $data = $this->_prepareData($data);
        return $this->getTpl('alert-info', $title, $data, $isBlock);
    }

    /**
     * @param string $str
     * @param mixed $val
     * @param bool $isBlock
     * @return string
     */
    public function warning($str, $val = null, $isBlock = false)
    {
        $title = (null == $val)? '' : $str;
        $data = (null == $val)? $str : $val;
        $data = $this->_prepareData($data);
        return $this->getTpl('', $title, $data, $isBlock);
    }

    /**
     * @param mixed $data
     * @return string
     */
    protected function _prepareData($data)
    {
        if(is_string($data)){
            return $data;
        }
        $str = '<ul>';
        foreach($data as $row) {
            $str .= '<li>'.$row.'</li>';
        }
        $str .= '</ul>';
        return $str;
    }
}
