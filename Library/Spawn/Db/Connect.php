<?php
/**
* Spawn Framework
*
* db connect
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Db
*/
namespace Spawn\Db;

class Connect
{
    /**
     *
     * @var \Spawn\Db
     */
    protected $_db;

    /**
     *
     * @param PDO $data
     */
    public function connect($data = 'Default')
	{		
		if( is_object($data) AND $data instanceof \PDO ){
			$this -> setDb($data);
		}elseif(is_array($data) AND \Spawn\Arr::isAssoc($data)){
			$this -> arrayAssocConnect($data);	
		}elseif(is_string($data)){
		    $this -> configConnect($data);
		}else{
			throw new ConnectException('Unknown configuration !');
		}		
	}

        /**
         *
         * @param string $name
         * @return Connect
         */
	public function register($name = 'Default')
	{
	    $dbm = new Manager();
	    $dbm -> setDb($name, $this -> _db);
	    return $this;
	}

        /**
         *
         * @param string $name
         * @return Connect
         */
	public function unregister($name = 'Default')
	{
	    $dbm = new Manager();
	    $dbm -> deleteDb($name);
	    return $this;
	}

        /**
         *
         * @param array $data
         * @return Connect
         */
    public function arrayAssocConnect(array $data)
    {
        $data['driver_options'] = (isset($data['driver_options']))? $data['driver_options'] : array();	
		$this -> _db = new \PDO(
			$data['dsn'],
			$data['user'],
			$data['pass'],
			$data['driver_options']
		);
		return $this;
    }


    /**
     *
     * @param string $data
     * @return Connect
     */
    public function configConnect($data)
    {
        $cfg = \Spawn\Config::load('Database') -> get($data);
		if(!$cfg) throw new ConnectException('Config "'.$data.'" not found in database configurations !');	
			
		$cfg['driver_options'] = (isset($cfg['driver_options']))? $cfg['driver_options'] : array();	
		$this -> _db = new \PDO(
			$cfg['dsn'],
			$cfg['user'],
			$cfg['pass'],
			$cfg['driver_options']
		);
		return $this;
    }

    /**
     *
     * @param \PDO $pdo
     * @return Connect
     */
    public function setDb(\PDO $pdo)
    {
		$this -> _db = $pdo;
		return $this;
    }

    /**
     *
     * @return \PDO
     */
	public function getDb()
	{
	    return $this -> _db;
	}
	
}//connect

class ConnectException extends \Exception {}
