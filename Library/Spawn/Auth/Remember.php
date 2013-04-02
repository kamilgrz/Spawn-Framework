<?php
/**
* Spawn Framework
*
* Class to remember user
*
* @author  Paweł Makowski
* @copyright (c) 2013 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Auth
*/
namespace Spawn\Auth;

use \Spawn\Cookie as Cookie;
use \Spawn\Orm as Orm;

class Remember
{
	/**
	* @var integer
	*/
	protected $_id;
	
	/**
	* cookie expire
	*
	* @var integer
	*/
	protected $_expire;
	
	/**
	* @var \Spawn\Request
	*/
	protected $_request;

	/**
	* @param integer $id user in
	*/
	public function __construct($id = null)
	{
		$this->_id = Cookie::get('spawn_user_remember2', $id);
		$config = new \Spawn\Config('Auth');
		$this->_expire = $config->get('remember.expire');
		$this->_request = new \Spawn\Request();
	}
	
	/**
	* @param integer $id user id
	* @return self
	*/
	public function setId($id)
	{
		$this->_id = $id;
		return $this;
	}
	
	/**
	* @return integer
	*/
	public function getId()
	{
		return $this->_id;
	}

	/**
	* remember user
	* 
	* @return bool
	*/
	public function remember($id = null)
	{
		$this->_id = (null == $id)? $this->_id : $id;
		if($this->_id < 1){
			throw new RememberException('Class Remember: User Id not found!');
		}
		Cookie::set('spawn_user_remember2', $this->_id, $this->_expire);
		
		$model = Orm::factory('user_remember')->find($this->_id);
		if($model->user_id > 0){
			$model->token = $this->_getToken();
		 	return $model->save();
		}
		$model->insert(array(
			'user_id' => $this->_id,
			'token' => $this->_getToken()
		));
	}
	
	/**
	* 
	* @return bool
	*/
	public function isRemembered()
	{
		if(Cookie::get('spawn_user_remember2')){
			$model = Orm::factory('user_remember')
			->where('token', $this->_getToken())
			->find($this->_id);
			if($model->user_id > 0)
			{
				return true;
			}
		}
		return false;
	}
		
	/**
	* delete cookie and db row
	* @return self
	*/
	public function forget()
	{
		Orm::factory('user_remember')->delete($this->_id);
		Cookie::delete('spawn_user_remember2');
		return $this;
	}
	
	/**
	* @return string
	*/
	protected function _getToken()
	{
		return md5($this->_request->ip().$this->_request->userAgent());
	}
}

class RememberException extends \Exception {}
