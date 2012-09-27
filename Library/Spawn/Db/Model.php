<?php
/**
* Spawn Framework
*
* Db model
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Db
*/
namespace Spawn\Db;
abstract class Model
{	
	/**
    * @var Db
    */
	protected $_db;

    /**
    * data to Db::from
    * @var string
    */
	protected $_from = null;
	
	/**
	*load sf_db to $db
	*/
	public function __construct()
	{
		$this -> _db = new \Spawn\Db();
		if(null != $this -> _from){
			$this -> _db -> from($this -> _from);
		}		
	}
}//model
