<?php
/**
* Spawn Framework
*
* Session
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;

class Session
{
    /**
    * @var string
    */ 
    protected $_name;
    
	/**
	*create session instance
	*
        *@param string $name
	*@return Session
	*/
	public static function load($name = 'Default')
	{
	    $registry = new Registry('Sf');
		if( false == $registry -> isRegistered('session') ){
			throw new SessionException('Session was not found in the registry');	
		}
		return $registry -> get('session');
	}

        /**
         *
         * @return Session
         */
	public function register()
	{
	    $registry = new Registry('Sf');
	    $registry -> set('session', $this);	
	    return $this;
	}
	
	/**
         *
         * @param string $name
         */
	public function __construct($name = 'Default')
	{				
	    $this -> name = $name;
		//destroy session if is isset
		$this -> destroy();		
		session_name($name);
		session_start();			
	}

        /**
         *
         * @return bool
         */
	public function isSecured()
	{
	    try{
	        $this -> validSecurity();  
	        return true;  
	    }catch(SessionException $e){
	        $this -> _error = $e -> getMessage();
	        return false;
	    }
	}

        /**
         *
         * @param integer $sTime
         * @return true|SessionException
         */
	public function validSecurity($sTime = null)
	{
	    $req = new Request();
		
		//valid	
		if( !isset($_SESSION['sfValid']) ){
			//if is first declare	
			$_SESSION['sfValid'] = 1;				
		}else{
			//valid
			if( $_SESSION['sfIp'] !== $_SERVER['REMOTE_ADDR'] ){
				throw new SessionException('Invalid IP');
			}
			if( $_SESSION['sfAgent'] !== $_SERVER['HTTP_USER_AGENT'] ){
				throw new SessionException('Invalid agent');
			}
			if( $_SESSION['sfSID'] !== session_id() ){
				throw new SessionException('Invalid SID');
			}
			if( null != $sTime ){
				$vtime = time() - $_SESSION['sfTime'];
				
				if($vtime > $sTime){
					throw new SessionException('Invalid session time');
				}
			}								
		}
			
		//params					
		$_SESSION['sfIp'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['sfAgent'] = $_SERVER['HTTP_USER_AGENT'];
		$_SESSION['sfTime'] = time();	
		$_SESSION['sfSID'] = session_id();
		return true;
	}

	/**
         * regenerate session id
         * @return Session
         */
	public function regenerate()
	{
		session_regenerate_id();
		$_SESSION['sfSID'] = session_id();
                return $this;
	}
	
	/**
         *
         * @param string $name
         * @param string $val
         * @return Session
         */
	public function set($name, $val)
	{
		$_SESSION[ $name ] = $val;
		return $this;
	}
	
	/**
         *
         * @param string $name
         * @param string $or
         * @return string
         */
	public function get($name, $or = null)
	{
		return ( isset($_SESSION[ $name ]) )? $_SESSION[ $name ] : $or;
	}
	
	
	/**
         *
         * @param string $name
         * @param string $val
         * @return Session
         */
	public function setFlash($name, $val)
	{
		$_SESSION['sfFlash'][ $name ] = $val;
		return $this;
	}
	
	/**
         * return nad destroy param
         * @param string $name
         * @param string $or
         * @return string
         */
	public function getFlash($name, $or = null)
	{
		$toGet = ( isset($_SESSION['sfFlash'][ $name ]) )? $_SESSION['sfFlash'][ $name ] : $or;
		if( isset($_SESSION['sfFlash'][ $name ]) ) unset($_SESSION['sfFlash'][ $name ]);
		return $toGet;
	}
	
	/**
         *
         * @param string $name
         * @return Session
         */
	public function delete($name)
	{
		if( isset($_SESSION[ $name ]) ){
			unset($_SESSION[ $name ]);
		}
		return $this;
	}
	
	/**
         * delete all $_SESSION values
         * @return Session
         */
	public function deleteAll()
	{
		if(isset($_SESSION)){
			$_SESSION = array();
		}
                return $this;
	}
	
	/**
         * destroy session
         * @return Session
         */
	public function destroy()
	{
		if( isset($_SESSION) ){
			$this -> deleteAll();
			unset( $_SESSION );			
	   		session_destroy(); 
   		}
                return $this;
	}
	
}//session

class SessionException extends \Exception {}
