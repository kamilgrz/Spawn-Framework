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
     * @var array
     */
    protected $_alert = array('error'=>'alert-error', 'info'=>'alert-info', 'success'=>'alert-success', 'warning'=>'');

    /**
     *
     */
    public function __construct()
    {
        $this->_registry = new \Spawn\Registry('Sf.Alert');
        $this->_session = \Spawn\Session::load();
    }

    /**
     * @param $str
     * @param null $val
     * @return $this
     */
    public function setError($str, $val = null)
    {
        return $this->_setData('error', $str, $val);
    }

    /**
     * @param $str
     * @param null $val
     * @return $this
     */
    public function setInfo($str, $val = null)
    {
        return $this->_setData('info', $str, $val);
    }

    /**
     * @param $str
     * @param null $val
     * @return $this
     */
    public function set($str, $val = null)
    {
        return $this->_setData('warning', $str, $val);
    }

    /**
     * @param $str
     * @param null $val
     * @return $this
     */
    public function setSuccess($str, $val = null)
    {
        return $this->_setData('success', $str, $val);
    }

    /**
     * @param $name
     * @param $str
     * @param null $val
     * @return $this
     */
    protected function _setData($name, $str, $val = null)
    {
        $title = (null == $val)? '' : $str;
        $val = (null == $val)? $str : $val;

        $this->_registry->set($name.'.title', $title);
        $this->_registry->set($name, $val);

        return $this;
    }

    /**
     * @param $str
     * @param null $val
     * @return $this
     */
    public function setErrorFlash($str, $val = null)
    {
        return $this->_setDataFlash('error', $str, $val);
    }

    /**
     * @param $str
     * @param null $val
     * @return $this
     */
    public function setSuccessFlash($str, $val = null)
    {
        return $this->_setDataFlash('success', $str, $val);
    }

    /**
     * @param $str
     * @param null $val
     * @return $this
     */
    public function setInfoFlash($str, $val = null)
    {
        return $this->_setDataFlash('info', $str, $val);
    }

    /**
     * @param $str
     * @param null $val
     * @return $this
     */
    public function setFlash($str, $val = null)
    {
        return $this->_setDataFlash('warning', $str, $val);
    }

    /**
     * @param $name
     * @param $str
     * @param null $val
     * @return $this
     */
    protected function _setDataFlash($name, $str, $val = null)
    {
        $title = (null == $val)? '' : $str;
        $val = (null == $val)? $str : $val;

        $this->_session->setFlash($name.'.title', $title);
        $this->_session->setFlash($name, $val);

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
        return $this->_getData('success', $isBlock);
    }

    /**
     * @param bool $isBlock
     * @return string
     */
    public function getInfo($isBlock = false)
    {
        return $this->_getData('info', $isBlock);
    }

    /**
     * @param bool $isBlock
     * @return string
     */
    public function getError($isBlock = false)
    {
        return $this->_getData('error', $isBlock);
    }

    /**
     * @param bool $isBlock
     * @return string
     */
    public function get($isBlock = false)
    {
        return $this->_getData('warning', $isBlock);
    }

    /**
     * @param $name
     * @param bool $isBlock
     * @return string
     */
    protected function _getData($name, $isBlock = false)
    {
        $title = $this->_session->getFlash($name.'.title',$this->_registry->get($name.'.title'));
        $data = $this->_session->getFlash($name,$this->_registry->get($name));
        if($data == null) return '';
        $data = $this->_prepareData($data);
        return $this->getTpl($this->_alert[$name], $title, $data, $isBlock);
    }

    /**
     * @param string $str
     * @param mixed $val
     * @param bool $isBlock
     * @return string
     */
    public function error($str, $val = null, $isBlock = false)
    {
        return $this->_data('error', $str, $val, $isBlock);
    }

    /**
     * @param string $str
     * @param mixed $val
     * @param bool $isBlock
     * @return string
     */
    public function success($str, $val = null, $isBlock = false)
    {
        return $this->_data('success', $str, $val, $isBlock);
    }

    /**
     * @param string $str
     * @param mixed $val
     * @param bool $isBlock
     * @return string
     */
    public function info($str, $val = null, $isBlock = false)
    {
        return $this->_data('info', $str, $val, $isBlock);
    }

    /**
     * @param string $str
     * @param mixed $val
     * @param bool $isBlock
     * @return string
     */
    public function warning($str, $val = null, $isBlock = false)
    {
        return $this->_data('warning', $str, $val, $isBlock);
    }

    /**
     * @param $name
     * @param $str
     * @param null $val
     * @param bool $isBlock
     * @return string
     */
    public function _data($name, $str, $val = null, $isBlock = false)
    {
        $title = (null == $val)? '' : $str;
        $data = (null == $val)? $str : $val;
        $data = $this->_prepareData($data);
        return $this->getTpl($this->_alert[$name], $title, $data, $isBlock);
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
