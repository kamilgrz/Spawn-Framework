<?php
/**
* Spawn Framework
*
* Db manager
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Db
*/
namespace Spawn\Db;

class Manager
{
    /**
     * @var Registry
     */
    protected $_registry;

    public function __construct()
    {
        $this -> _registry = new \Spawn\Registry('Sf');
    }

    /**
     *
     * @return Connect
     */
	public function getDb()
	{
	    $name = $this -> _registry -> get('DbName');
	    if( !$this -> _registry -> isRegistered('Db'.$name) ){
	        throw new ManagerException('Db '.$name.' not found!');
	    }
		$pdo = $this -> _registry -> get('Db'.$name);
		$dbc = new Connect();
		$dbc -> connect($pdo);
		return $dbc;
	}

        /**
         *
         * @param string $name
         * @return Manager
         */
	public function deleteDb($name = false)
	{
	    $name = (false == $name)? $this -> _registry -> get('DbName') : $name;
	    if( !$this -> _registry -> isRegistered('Db'.$name) ){
	        throw new ManagerException('Db '.$name.' not found!');
	    }
		$this -> _registry -> delete('Db'.$name);
		return $this;
	}

        /**
         *
         * @param string $name
         * @return Manager
         */
	public function setUseDbName($name)
	{
	    $this -> _registry -> set('DbName', $name);
            return $this;
	}

        /**
         *
         * @return string
         */
	public function getUseDbName()
	{
	    return $this -> _registry -> get('DbName');
	}

        /**
         *
         * @param string $name
         * @param Db\Connect|PDO $db
         * @return Manager
         */
	public function setDb($name, $db)
	{
	    $db = ( $db instanceof \Spawn\Db\Conenct )? $db -> getDb() : ($db instanceof \PDO)? $db : false;
	    if(false === $db){
	        throw new ManagerException('Invalid Db param!');
	    }    
	    $this -> _registry -> set('DbName', $name);	
            $this -> _registry -> set('Db'.$name, $db );
            return $this;
	}
}

class ManagerException extends \Exception {}
