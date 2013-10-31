<?php
/**
* Spawn Framework
*
* Database
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Db
*/
namespace Spawn;

class Db
{
	/**
    * @var \PDO hander
    */
	protected $_db;
	
	/**
    * @var string 
    */
	protected $_sql;	

    /**
    * @var string
    */
	public $from;

    /**
    * @var array
    */
	public $select = array();

    /**
    * @var array
    */
	public $order;

    /**
    * @var array
    */
	public $group;

    /**
    * @var array
    */
	public $set = array();

    /**
    * @var integer
    */
	public $fetch = \PDO::FETCH_OBJ;

    /**
    * @var string
    */
	public static $lastSql = null;
	
	/**
	*arg to execute (bind)
	*
	* @var array
	*/
	protected $_sql_args = array();	
	
	/**
	*call method \PDO
	*
	*@throw DbException if method not found
	*@param string $method
	*@param array $args
	*@return mixed
	*/
	public function __call($method, array $args)
	{
		if(!method_exists($this -> _db,$method)){ throw new DbException('Method "'.$method.'" not found!'); }
		return call_user_func_array(array($this -> _db, $method), $args);				
	}
	
	/**
	*create new \PDO
	*
	*@throw DbException - if unknown $config
	*@param PDO|Db\Connect|false $Db fasle if we use Db\Manager
	*/
	public function __construct($db = false)
	{
	    if($db == false){
	        $dbm = new \Spawn\Db\Manager();
	        $this -> _db = $dbm -> getDb() -> getDb();
	    }else{
	        if( $db instanceof \Spawn\Db\Connect ) $db = $db -> getDb();
	        if( ! $db instanceof \PDO ) throw new DbException( 'Invalid param in __construct()!' ); 
	        $this -> _db = $db;
	    }
	}

	
	/**
	*add to select list new param
	*
	*@param string $search param
	*@return $this
	*/
	public function select($search = '*')
	{
		$this -> select[] = $search;
		return $this;		
	}
	
	/**
	*declare table name
	*
	*@param string $table name
	*@return $this
	*/
	public function from($table)
	{		
		$this -> from = $table;
		return $this;
	}
	
	/**
         *
         * @param array|string $where
         * @param string $valWhere
         * @param string $sep
         * @param bool $bind
         * @return Db
         */
	public function where($where, $valWhere='AND' ,$sep = 'AND', $bind = true)
	{		
		if(is_array($where)){
			if(count($where) < 1) return $this;
			$param=array();			
			foreach($where as $key => $val){
				$key = (false !== strpos($key, ' ') )? $key.' ' : $key.'=';
				if(true == $bind){
					$param[] = $key.' ? ';
					$this -> _sql_args[] = $val;	
				}else{
					$param[] = $key.$val;
				}			
			}
			$where = implode(' '.$valWhere.' ', $param);
		}else{
			$where = ( false !== strpos($where, ' '))? $where.' ' : $where.'=';
			if(true == $bind){
				$where = $where.'? ';
				$this -> _sql_args[] = $valWhere;
			}else{
				$where = $valWhere;
			}			
		}
		$where = ( !isset($this -> where) )?' WHERE '.$where : ' '.$sep.' '.$where;				
		$this -> where .= $where;
		return $this;
	}
	
	/**
         *
         * @param string $name
         * @param string|array $val
         * @param string $funct
         * @param string $sep
         * @return Db
         */
	public function whereFunct($name, $val, $funct = 'IN' ,$sep = 'AND')
	{		
		$q = ('IN' !== strtoupper($funct))? '?' : implode(',', array_fill(0, count($val), '?'));

		$in = $name.' '.$funct.'('.$q.')';
		$where = ( !isset($this -> where) )?' WHERE '.$in : ' '.$sep.' '.$in;	
		if(!is_array($val)){
			$this -> _sql_args[] = $val;
		}else{
			foreach($val as $key){
				$this -> _sql_args[] = $key;
			}
		}	
		$this -> where .= $where;
		return $this;
	}
	
		
	/**
	*add where with or separator to sql query
	*
	*@param array|string $where
	*@param string $valWhere
	*@return $this
	*/	
	public function orWhere($where, $valWhere = 'OR' )
	{
		$this -> where($where, $valWhere, 'OR');
		return $this;
	}
	
	/**
	*add order by to sql query 
	*
	*@param string $param order (id desc etc.)
	*@return $this
	*/	
	public function order($param)
	{
		$this -> order[] = $param;		
		return $this;
	}
	
	/**
	*add limit X,Y to sql query
	*
	*@param integer $from_record
	*@param integer $count_record
	*@return $this
	*/
	public function limit($from_record, $count_record = null )
	{
		$this -> limit = $from_record;
		if(null !== $count_record){
			$this -> offset = $count_record;
		}		
		return $this;
	}
	
	/**
	*join tables , declare relations and join type
	*
	*@param string $table name to join
	*@param array $where declare relations in 'where'
	*@param string $type join type (join, join left etc.)
	*@return $this
	*/
	public function join($table, array $where = array() ,$type = 'join')
	{
		$this -> from .= ' '.$type.' '.$table;
		$this -> where($where, null, 'AND', false);
		return $this;
	}
	
	/**
	*add ON t1=t2 to this -> from 
	*add on() when use join left etc.
	*
	*@param string $t1
	*@param string $t2
	*@param string $sep
	*@return $this
	*/
	public function on($t1, $t2, $sep='=')
	{
		$this -> from.=' ON '.$t1.$sep.$t2;
		return $this;
	}
	/**
	*add group by
	*
	*@param string $param name to group
	*@return $this
	*/
	public function group($param)
	{
		$this -> group[] = $param;				
		return $this;
	}
	
	/**
         * create sql query string
         */
	public function sqlCreate()
	{		
		if( isset($this -> where) ) $this -> _sql .= $this -> where; 
		if( count($this -> group) > 0 ) $this -> _sql .= ' GROUP BY '.implode(',', $this -> group);	
		if( count($this -> order) > 0 ) $this -> _sql .= ' ORDER BY '.implode(',', $this -> order);			
		if( isset($this -> limit) ) $this -> _sql .= ' LIMIT '.$this -> limit; 
		if( isset($this -> offset) ) $this -> _sql .= ' , '.$this -> offset; 
		self::$lastSql = $this -> _sql;	
	}
	
	/**
         * clear sql query string etc.
         */
	public function sqlClear()
	{		
		$this -> _sql = null;
		$this -> _sql_args =
		$this -> select =		
		$this -> order =
		$this -> group = array();
		$this -> from = null;
		unset($this -> where, $this -> limit, $this -> offset);
	}
	
	/**
	*execute sql query
	*
	*@return object
	*/
	public function execute()
	{
		$this -> sqlCreate();	
		$this -> query = $this -> _db -> prepare($this -> _sql);
		$this -> query -> execute($this -> _sql_args);
		$this -> sqlClear();
		return $this -> query;
	}
	
	
	/**
	*execute sql query and return fetch result
	*
	*@param string $sql query
	*@param array $args if we must binding values
	*@param+ string|int $+ if self param is not array
	*@return object
	*/
	public function query( $sql, $args=null )
	{
		$this -> query = $this -> _db -> prepare($sql);
		
		//if $args = array(1,2,3,4,5)
		if(is_array($args)){
			$this -> query -> execute($args);
		}else{
		//if $args = query('sql',arg1,arg2,arg3...)			
			$args = func_get_args();
			array_shift($args);
			$this -> query -> execute($args);
		}
		return $this -> query;
	}
	
	
	/**
	*execute sql query and return fetch result
	*
	*@param string $sql query
	*@param array $args if we must binding values
	*@return object
	*/
	public function fetch($sql, $args = null)
	{
		return $this -> query($sql, $args) -> fetch($this -> fetch);
	}
	
	/**
	*execute sql query and return fetchAll result
	*
	*@param string $sql query
	*@param array $args if we must binding values
	*@return object
	*/
	public function fetchAll($sql, $args = null)
	{
		return $this -> query($sql, $args) -> fetchAll($this -> fetch);
	}
	
	/**
	*declare fetch type to assoc
	*
	*@return $this
	*/
	public function asArray()
	{
		$this -> fetch = \PDO::FETCH_ASSOC;
		return $this;
	}
	
	/**
	*declare fetch type
	*
	*@param integer $type fetch type
	*@return $this
	*/
	public function fetchType($type)
	{
		$this -> fetch = $type;
		return $this;
	}
	
	/**
         * if no select , select is all (*)
         */
	protected function _createSelectQuery(){		
		if(count($this -> select) < 1) $this -> select = array('*');
		$this -> _sql = 'SELECT '.implode(',',$this -> select).' FROM '.$this -> from;
	}
	
	/**
	*return one row
	*
	*@return mixed
	*/
	public function find()
	{
		$this -> _createSelectQuery();
		$this -> query = $this -> execute();
		return $this -> query -> fetch($this -> fetch);
	}
	
	/**
	*return one param with row
	*
	*@param integer $nr
	*@return string
	*/
	public function getParam($nr=1)
	{
		$this -> _createSelectQuery();
		$stmt=$this -> execute();
		$stmt -> bindColumn($nr, $param); 
		$stmt -> fetch(); 
		return $param;
	}
	
	/**
	*execute sql query and return fetchAll 
	*
	*@param integer $from_record
	*@param integer  $count_record
	*@return object
	*/
	public function findAll($from_record = null, $count_record = null)
	{
		//if we have params and need limit
		if(null !== $from_record){
			$this -> limit($from_record, $count_record);
		}
		
		$this -> _createSelectQuery();
		
		//execute
		$this -> query = $this -> execute();
		return $this -> query -> fetchAll($this -> fetch);
	}
	
	/**
	*return rows count
	*
	*@param string $table name
	*@return integer
	*/
	public function count($table = null)
	{
		if(count($this -> select)<1 ) $this -> select( 'COUNT(*)' );		
		if(null != $table) $this -> from = $table;	
					
		//execute
		$this -> _createSelectQuery();
		$query = $this -> execute();	
			
		return $query -> fetchColumn();	
	}
	
	/**
    * mysql last instert id
    *
	*@return integer
    */
	public function insertId()
	{
		return $this -> _db -> lastInsertId();
	}
	
	
	/**
	*insert one row
	*
	*@param string $table name
	*@param array $values // array= 'db_param_name'=>'any_value'
	*@return integer
	*/
	public function insert($table, array $values)
	{	
		$sql = 'INSERT INTO '.$table;	
		$sql .= ' ('.implode(',', array_keys($values) ).') VALUES';	
		$sql .= ' ('.implode(',', array_fill(0, count($values), '?') ).')';
		self::$lastSql = $sql;
		
		//execute
		$this -> query = $this -> _db -> prepare($sql);
		$this -> query -> execute(array_values($values));
		return $this -> query -> rowCount();
	}
	
	/**
	*insert many row
	*
	*@param string $table name
	*@param array $values
	*@return integer
	*/
	public function insertAll($table, array $values)
	{
		$sql = 'INSERT INTO '.$table;
		$sql .= ' ('.implode(',', array_keys($values[0]) ).') VALUES';
		
		//create new row string to sql query 
		$val = array();
		$data = array();
		foreach($values as $key){
			$data[] = ' ('.implode(',', array_fill(0, count($key), '?') ).')';
			foreach($key as $key){
				$val[] = $key;
			}
		}
		
		$sql .= implode(',', $data);
		//execute
		$this -> query = $this -> _db -> prepare($sql);
		$this -> query -> execute(array_values($val));
		return $this -> query -> rowCount();
	}
	
	
	/**
	*delete rows from table
	*
	*@param string $table name
	*@param array $param to where // array= 'db_param_name'=>'any_value'
	*@param string $sep
	*@return integer
	*/	
	public function delete($table = null, array $param = array(), $sep = ' AND ')
	{
		$table = (null == $table)? $this -> from : $table;
		$this -> _sql = 'DELETE FROM '.$table;
		
		//create where x=? and y=? etc.		
		$this -> where($param, $sep);
		
		//execute query
		$this -> query = $this -> execute();
		return $this -> query -> rowCount();			
	}
	
	
	/**
	*set param 
	*
	*@param string $name
	*@param string $val new value
	*@return $this
	*/
	public function set($name, $val)
	{
		$this -> set[ $name ] = $val;
		return $this;
	}
	
	/**
	*update table 
	*
	*@param string $table name
	*@param array $value update values => array('row_name'=>'value_to_update')
	*@param array $where - param to where => array('row_name'=>'any_value')
    *@param string $sep
	*@return integer
	*/
	public function update($table = null , $values = array(), array $where = array() , $sep = ' AND ' )
	{
		$table = (null == $table)? $this -> from : $table;
		$this -> _sql = 'UPDATE '.$table.' SET ';
		
		//create update SET  x=? , y=? etc.	
		$sql_args = array();
		$param = array();
		if(is_array($values)){			
			//add $values to sql query
			foreach($values as $key => $val){
				$param[] = $key.'=? ';
				array_unshift($sql_args, $val);
			}
		}else{
			$this -> _sql .= $values;
		}
		
		//add param with set() to sql query
		foreach($this -> set as $key => $val){
			$param[] = $key.'=? ';
			array_unshift($sql_args ,$val);
		}		
		
		//update sql args
		foreach($sql_args as $key){
			array_unshift($this -> _sql_args, $key);
		}
		
		//implode set params
		$params=implode(', ', $param);
		$this -> _sql .= $params;
		
		//create where x=? and y=? etc.		
		$this -> where($where, $sep);
		
		//execute
		$this -> query = $this -> execute();
		return $this -> query -> rowCount();
	}
	
	/**
	*write last query string
	*
	*@return string
	*/
	public static function lastSql()
	{
		return self::$lastSql;
	}
	
}//db

class DbException extends \Exception{}
