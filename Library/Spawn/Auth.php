<?php
/**
* Spawn Framework
*
* Class to user authorisation
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;

class Auth
{
    /**
	* @var array
	*/
	protected $_config = array();

    /**
	* @var Session
	*/
	protected $_session;

    /**
	* @var Db
	*/
	protected $_db;

    /**
    *
    * @var array|bool
    */
    protected $_data = false;
	
		
	/**
	* load auth config
	*/
	public function __construct( $session = null , \Spawn\Db $db = null)
	{
	    $config = new Config('Auth');
		$this -> _config = $config -> getAll();
		$config -> clear();
		
		$this -> _db = (null == $db)? new Db() : $db;
		$this -> _session = (null == $session)? Session::load() : $session;
	}
	
	/**	
	* check whether the array is logged	
	*
	* @return bool
	*/
	public function valid()
	{
		if($this -> _session -> get($this -> _config['prefix'].'SfAuthId', 0) == 0){
			return false;
		}	
		return true;
	}
	
	/**
	* return logged user id
	*
	* @return integer
	*/
	public function id()
	{
		return $this -> _session -> get($this -> _config['prefix'].'SfAuthId');
	}
	
	/**
	* check whether the array is assoc
	*
	* @param string $name
	* @param string $pass
	* @return int|bool
	*/
	public function login($name, $pass)
	{	
		$user = $this -> _db
			 -> select($this -> _config['id'])
			 -> from($this -> _config['table'])
			 -> where( array( 
				$this -> _config['name'] => $name,
				$this -> _config['password'] => md5($pass.$this -> _config['salt'])
				 ) )
			 -> find();
		if($user == false){
			return false;
		}	
		$this -> _session -> set($this -> _config['prefix'].'SfAuthId', $user -> {$this -> _config['id']});
		return $user -> {$this -> _config['id']};
	}
	
	/**
	* logout user , destroy session
	*
	* @return Auth
	*/
	public function logout()
	{
		$this -> _session -> delete($this -> _config['prefix'].'SfAuthId');
		return $this;
	}
	
	/**
	* get user with db
	*
	* @param integer $id user id
	* @return object|bool
	*/
	public function getUser($id = null)
	{
		$id = ($id != null)? $id : $this -> _session -> get($this -> _config['prefix'].'SfAuthId');
		return $this -> _data = $this -> _db
			 -> select('*')
			 -> from($this -> _config['table'])
			 -> where($this -> _config['id'], $id)
			 -> find();
	}

    /**
    *
    * @param string $name
    * @return string
    */
    public function getParam($name)
    {
        $id = $this -> _session -> get($this -> _config['prefix'].'SfAuthId');
        return $this -> _db
			 -> select($name)
			 -> from($this -> _config['table'])
			 -> where($this -> _config['id'], $id)
			 -> getParam();    
    }
	
	/**
	* change password
	*	
	* @param string $pass new passowrd
	* @param integer $id 
	* @return integer 
	*/
	public function changePass($pass, $id = null)
	{
	    $id = (null != $id)? $id : $this -> _session -> get($this -> _config['prefix'].'SfAuthId');
		return  $this -> _db -> update($this -> _config['table'],
					array($this -> _config['password'] => md5($pass . $this -> _config['salt']) ),
					array($this -> _config['id'] => $id)
				);
	}
	
	/**
	* change param
	*
	* @param string $param name
	* @param string|integer $data
	* @param integer $id user id
	* @return integer 
	*/
	public function changeParam($param, $data, $id=null)
	{
		$id = (null != $id)? $id : $this -> _session -> get($this -> _config['prefix'].'SfAuthId');
		return  $this -> _db -> update($this -> _config['table'],
					array( $param => $data ),
					array( $this -> _config['id'] => $id )
				);
	}
	
	/**
	* change params
	*
	* @param array $data
	* @param integer $id user id
	* @return integer 
	*/
	public function changeParams(array $data, $id=null)
	{
		$id = (null != $id)? $id : $this -> _session -> get($this -> _config['prefix'].'SfAuthId');
		return  $this -> _db -> update($this -> _config['table'],
					$data,
					array( $this -> _config['id'] => $id )
				);
	}
	
	
	/**
	* add new user
	*
	* @param+ string
	* @return integer 
	*/
	public function add()
	{
		$toAdd = array();
		$args = func_get_args();
		foreach($this -> _config['toAdd'] as $key => $val){
                        $args[ $key ] = (isset($args[ $key ]))? $args[ $key ]: null;
			$toAdd[ $val ] = ($val != $this -> _config['password'])?
				 $args[ $key ] : md5($args[ $key ] . $this -> _config['salt']) ;
		}
		
		return $this -> _db -> insert($this -> _config['table'], $toAdd);
	}
	
	
	/**
	* check name isset
	*
	* @param string $name
	* @return bool
	*/
	public function nameIsset($name)
	{
		$id = $this -> _db
			 -> select($this -> _config['id'])
			 -> from($this -> _config['table'])
			 -> where($this -> _config['name'], $name)
			 -> getParam();	
		return ($id > 0)?	true : false;
	}
	
	/**
	* delete user
	*
	* @param integer $id user id
	* @return integer 
	*/
	public function delete($id)
	{
		return $this -> _db
			 -> delete(
				$this -> _config['table'],
				array($this -> _config['id'] => $id)
			);
	}

}//auth
