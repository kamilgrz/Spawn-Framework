<?php
/**
* Spawn Framework
*
* Math
*
* @author  Paweł Makowski
* @copyright (c) 2010-2013 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Db
*/
namespace Spawn;

class Orm 
{
	/**
         * @var string
         */
	protected $_tableName;
	
	/**
         * @var string
         */
	protected $_tableKey;
		
	/**
         * @var array
         */
	protected $_structure = array();
	
	/**
         * @var Db
         */
	protected $_db = null;
	
	/**
         * find result
         * @var mixed
         */
	protected $_find = null;

        /**
         *
         * @var array
         */
    protected $_filter = array();
	
	/**
	* @var array
	*/
	protected $_validError = array();
	
	/**
	* @var integer|string
	*/
	protected $_event = 0;
	
	protected $_request;
	
	/* MAGIC METHODS  */
	
	
	/**
         *
         * @param Db $db
         */
	public function __construct( Db $db = null)
	{
		$this -> _db = (null == $db)? new Db() : $db;			
		$this -> filter('Default');		
	}
	
	/**
	*use Db
	*
	*@param string $method
	*@param array $args
	*@return mixed
	*/
	public function __call($method, array $args)
	{	
		$return = call_user_func_array(array($this -> _db, $method), $args);
		return ($return instanceof \Spawn\Db) ? $this : $return;
		
	}
	
	/**
	*get param find result (needed if we use save())
	*
	*@param string $key
	*@param string $val
	*/
	public function __set($key, $val)
	{
		if( !is_object($this -> _find) ){
			$this -> _find[ $key ] = $val;
		}else{
			$this -> _find -> $key = $val;
		}
	}
	
	/**
	*return param with find()
	*
	*@param string $name
	*@return string
	*/
	public function __get($name)
	{
		if( null != $this -> _find ){
			if( is_array($this -> _find) ){
				if( isset($this -> _find[ $name ]) ) return $this -> _find[ $name ];
			}else{
				if( isset($this -> _find -> {$name}) ) return $this -> _find -> {$name};
			}
		}	
		if( isset($this -> {$name}) ) return $this -> {$name};
		return null;
		
	}
	
	
	/*ORM METHODS*/
	
	
	/**
	*load orm file (create if not exists)
	*
	*@param string $name table/ormClass name
        *@param Db $db
	*@return object
	*/
	public static function factory($name,Db $db = null)
	{
	    $cname = str_replace('_', '\\', $name);
		$class = '\Model\Orm\\'.$cname;
		if( !class_exists($class) ) self::createOrmModelFile($name);
		
		return new $class( $db );
	}
	
	/**
	* @param object $req
	* @return self
	*/
	public function setRequest(\Spawn\Request $req)
	{
		$this->_request = $req;
		return $this;
	}
	
	/**
	* @return \Spawn\Request
	*/
	public function getRequest()
	{
		if(null == $this->_request){
			$this->_request = new \Spawn\Request();
		}
		return $this->_request;
	}
	
	/**
	* set find data (data to save())
	*
	* @param array
	* @param bool $secure unset primary key from array
	* @return self
	*/
	public function setData(array $data, $secure = true)
	{
		$this -> _find = $data;
		if($secure == true && isset($this->_find[$this->_tableKey])){
			unset($this->_find[$this->_tableKey]);
		}
		return $this;
	}
	
	/**
	* get find data (data to save())
	*
	* @return array
	*/
	public function getData()
	{
		return $this -> _find;
	}
	
	/**
	* set event name 
	*
	* @return self
	*/
	public function setEvent($name)
	{
		$this->_event = $name;
		return $this;
	}
	
	/**
	* get event name (use in getFrom/getRules)
	*
	* @return integer|string
	*/
	public function getEvent()
	{
		return $this->_event;
	}	
	
	/**
	* get form structure
	*
	* @return array
	*/
	public function getForm()
	{
		return array();
	}
	
	/**
	* @return self
	*/
	public function search()
	{
		return $this;
	}
	
	/**
	* declare default values to insert/select/update
	*
	* @return array
	*/
	public function loadDefaultData()
	{
	}
	
		
	/**
	* rules to Valid()->setRules()
	*
	* @param string $name
	* @return array
	*/
	public function getRules($acl = null)
	{
		return array();
	}
		
	/**
	*find one record
	*
	*@param integer|string $id
	*@param bool $struct
	*@return $this
	*/
	public function find($id = null, $struct = false)
	{		
	
		if( count($this -> _db -> select) < 1 ) $this -> _db -> select($this -> _tableName . '.*');
		$this -> _TableNameExists();	
					
		if(null !== $id ){
			 $this -> _find = $this -> _db -> where( $this -> _tableKey, $id ) -> find();			
		}else{
			 $this -> _find = $this -> _db -> find();
		}
		
		if(true == $struct ) $this -> _find = ($this -> _find)? $this -> _find : Arr::fill($this -> _structure);
		$this -> _find =  $this -> _useFilter($this -> _find);
		return $this;	
	}
	
	/**
	*execute sql query and return fetchAll 
	*
	*@param integer $from_record
	*@param integer $count_record
	*@return array|object
	*/
	public function findAll($from_record = null, $count_record = null)
	{
		if(count($this -> _db -> select) < 1 ) $this -> _db -> select( $this -> _tableName.'.*' );
		
		$this -> _TableNameExists();
			
		$values = $this -> _db -> findAll( $from_record, $count_record );
                $values = $this -> _useFilter($values);
                return $values;
	}

        /**
         *
         */
	protected function _TableNameExists()
	{
		if( 
			false === strpos($this -> _db -> from, $this -> _tableName)
			or 
			strpos($this -> _db -> from, $this -> _tableName) > 0
		){
			$this -> _db -> from = $this -> _tableName.' '.$this -> _db -> from;
		}
	}
	
	/**
	*return one param with row
	*
	*@param integer $nr
	*@return string
	*/
	public function getParam($nr=1)
	{
		if( !strpos($this -> _db -> from, $this -> _tableName) ) $this -> _db -> from = $this -> _tableName.' '.$this -> _db -> from;
		$values = $this -> _db -> getParam($nr);
                $values = $this -> _useFilter($values);
                return $values;
	}
	
	/**
         * @return array (use when we used find())
         */
	public function toArray()
	{		
		return ($this -> _find)? new \arrayObject($this -> _find) : array();
	}
	
	/**
	* before save
	* return false to break save
	* @return bool|none
	*/
	public function beforeSave()
	{ 
		return true; 
	}
	
	/**
	*insert or update $_find values (use after find() method) 
	*
	*@param array $where
	*@return $this
	*/
	public function save($where = null)
	{
		if($this->beforeSave()!==false){
			return $this->_save($where);
		}
		return false;
	}
	
	/**
	*insert or update $_find values (use after find() method) 
	*
	*@param array $where
	*@return $this
	*/
	protected function _save($where = null)
	{
		$info=0;		
		if(null != $this -> _find){			
			$req = array();
			foreach($this -> _find as $key => $val){
				if( in_array($key, $this -> _structure) ){
					$req[ $key ] = $val;
				}
			}

            $req = $this -> _useFilter($req);
		
            $db = new Db;
			
			if( isset($req[ $this -> _tableKey ]) ){
				if(is_array($this -> _find)){
					$where = ( null != $where )? $where:
						array( $this -> _tableKey => $this -> _find[$this -> _tableKey] );				
				}else{
					$where = (null != $where )? $where:
						array( $this -> _tableKey => $this -> _find -> {$this -> _tableKey} );				
				}				
				$info = $db -> update($this -> _tableName, $req, $where);
			}else{
				$info = $db -> insert($this -> _tableName, $req);
			}	
		}
		return $info;		
	}
		
	/**
	*insert one row
	*
	*@param array $values // array= 'db_param_name'=>'any_value'
	*@return integer
	*/
	public function insert(array $values)
	{
        $values = $this -> _useFilter($values);
		$this -> _db -> sqlClear();
		return $this -> _db -> insert($this -> _tableName, $values);
	}
	
	/**
	*insert many row
	*
	*@param array $values
	*@return integer
	*/
	public function insertAll(array $values)
	{
        $values = $this -> _useFilter($values);
		return $this -> _db -> insertAll($this -> _tableName, $values);
	}
	
	/**
	*delete rows from table
	*
	*@param array|string|integer $param to where - if int | string - use tableKey to param name
	*@param string $sep 
	*@return integer
	*/
	public function delete( $param=array(), $sep = ' AND ')
	{
		$param = ( is_array($param) )? $param : array($this -> _tableKey => $param);
		return $this -> _db -> delete($this -> _tableName, $param, $sep = ' AND ');
	}
	
	/**
         *
         * @param array $values
         * @param array|integer $where
         * @param string $sep
         * @return integer
         */
	public function update( $values = array(), $where = array() , $sep = ' AND ')
	{
		$where = (is_array($where))? $where : array($this -> _tableKey => $where);
		return $this -> _db -> update($this -> _tableName, $values, $where, $sep );
	}
	
	/**
         *
         * @param array $req
         * @return integer
         */
	public function insertRequest(array $req)
	{	
		$req = $this -> deleteUnusedValues($req);
		$req = $this -> _useFilter($req);
		//execute
		return $this -> _db -> insert($this -> _tableName, $req);
	}
	
	/**
         *
         * @param array $req
         * @param array|integer $where
         * @param string $sep
         * @return integer
         */
	public function updateRequest(array $req, $where = array(), $sep = ' AND ')
	{
		
		$req = $this -> deleteUnusedValues($req);
		$req = $this -> _useFilter($req);
		//create where
		$where = (is_array($where))? $where : array($this -> _tableKey => $where);
		
		//execute
		return $this -> _db -> update($this -> _tableName, $req, $where, $sep);
	}
	
	/**
	*use config to join
	*
	*@param string $name config join name
	*@return $this
	*/
	public function with($name)
	{
		$this -> _join[ $name ]['where'] = ( isset($this -> _join[ $name ]['where']) )? $this -> _join[ $name ]['where'] : array();
		$this -> _db -> join($name, $this -> _join[ $name ]['where'], $this -> _join[ $name ]['type']);
		
		if(isset($this -> _join[ $name ]['on'])){
			$this -> _join[ $name ]['on'][2] = ( isset($this -> _join[ $name ]['on'][2]) ) ? $this -> _join[ $name ]['on'][2] : '=';
			$this -> _db -> on(
				$this -> _join[ $name ]['on'][0], 
				$this -> _join[ $name ]['on'][1], 
				$this -> _join[ $name ]['on'][2]
				);
		}			
		if(isset($this -> _join[ $name ]['select'])){
			$this -> _db -> select($this -> _join[ $name ]['select']);
		}	
		if(isset($this -> _join[ $name ]['group'])){
			$this -> _db -> group($this -> _join[ $name ]['group']);
		}	
		
		return $this;
	}
	
	/**
         * @return integer
         */
	public function count()
	{
		if( !strpos($this -> _db -> from, $this -> _tableName) ) $this -> _db -> from = $this -> _tableName.' '.$this -> _db -> from;
		return $this -> _db -> count();
	}
		
	/**
	*create orm file
	*
	*@throw OrmException - if table not exists
	*@param string $name table name
	*/
	public static function createOrmModelFile($name)
	{
		$_db = new Db();
			
		$isTable = (bool)$_db -> query('show tables like "'.$name.'"') -> fetch();
		if($isTable == false){
			throw new OrmException('Table "'.$name.'" not found!');
		}
		
		$dirs = explode('_',$name);
		
		unset($dirs[ count($dirs)-1 ]);
		$dir = ROOT_PATH . 'Application' . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Orm';
		foreach($dirs as $key){
			$dir .= DIRECTORY_SEPARATOR . $key;
			if(!file_exists($dir)){
			    mkdir($dir, 0755);
			}    
		}
		
		//get table struct
		$structTable = $_db -> query('show columns from '.$name) -> fetchAll(\PDO::FETCH_ASSOC);
		
		//declare table struct and key		
		$struct = array();
		$pri = null;
		
		$rules = '';
		$form = '';
		$search = '';
		foreach($structTable as $key){
			$search .= '        if($request->post(\''.$key['Field'].'\')) $this->_db->where(\'`'.$name.'`.`'.$key['Field'].'` LIKE\', \'%\'.$request->post(\''.$key['Field'].'\').\'%\');'.PHP_EOL;
			$struct[] = $key['Field'];			
						
			if(!($pri == null AND trim($key['Key']) == 'PRI')){
				$ftype = (strpos($key['Type'], 'text')!==false)? 'textarea' : 'text';
				$form .= '            \''.str_replace('_',' ',$key['Field']).'\' => array(\'name\' => \''.$key['Field'].'\', \'type\' => \''.$ftype.'\', \'value\'=>$request->post(\''.$key['Field'].'\', $this->'.$key['Field'].')), '.PHP_EOL;
			
				$rule='';
				if(strpos($key['Type'], 'varchar') !== false){ 
					$max = preg_replace('#varchar\((.*)\)#', '$1', $key['Type']);
					$rule.='\'maxStrLength\' => '.$max .', ';
				}
				if(strpos($key['Type'], 'datetime') !== false or strpos($key['Type'], 'timestamp') !== false){ 
					$rule.='\'regex\' => \'/^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/\', ';
				}
				if(strpos($key['Type'], 'date') !== false){ 
					$rule.='\'regex\' => \'/^\d{4}-\d{1,2}-\d{1,2}$/\', ';
				}
				if(strpos(strtolower($key['Field']), 'mail') !== false){ 
					$rule.='\'mail\' => \'\', ';
				}
				if(strpos(strtolower($key['Field']), 'url') !== false or strpos(strtolower($key['Field']), 'href') !== false){ 
					$rule.='\'url\' => \'\', ';
				}
				$rules .= '            \''.$key['Field'].'\' => array('.$rule.' \'required\' => true), '.PHP_EOL;
			}else{
				$pri = $key['Field'];	
			}	
		}
		$struct = implode('\', \'', $struct);
		
		//$cname = str_replace('_', '\\', $name);
	    $cName = ( strpos($name, '_') === false )? $name: substr($name, strripos($name, '_') + 1 ); 
	    $cSpace = substr($name, 0, strripos($name, '_') );
		$cSpace = str_replace('_', '\\', $cSpace);
		$cSpace  = ($cSpace != '')? '\\'.$cSpace : '';
		//create html		
		$file = '<?php'.PHP_EOL;
		$file .= 'namespace Model\Orm'.$cSpace.';'.PHP_EOL;
		$file .= 'class '.$cName.' extends \Spawn\Orm'.PHP_EOL;
		$file .= '{'.PHP_EOL;
		$file .= '    protected $_tableName = \''.$name.'\';'.PHP_EOL;
		$file .= '    protected $_tableKey = \''.$pri.'\';'.PHP_EOL;
		$file .= '    protected $_structure = array(\''.$struct.'\');'.PHP_EOL;
		$file .= ''.PHP_EOL;
		$file .= '    public function getRules($acl = null)'.PHP_EOL;
		$file .= '    {'.PHP_EOL;
		$file .= '        return array('.PHP_EOL;
		$file .= $rules;
		$file .= '        );'.PHP_EOL; 
		$file .= '    }'.PHP_EOL;
		$file .= ''.PHP_EOL;
		$file .= '    public function getForm()'.PHP_EOL;
		$file .= '    {'.PHP_EOL;
		$file .= '        $request = $this->getRequest();'.PHP_EOL;
		$file .= '        return array('.PHP_EOL;
		$file .= $form; 
		$file .= '        );'.PHP_EOL; 
		$file .= '    }'.PHP_EOL;
		$file .= ''.PHP_EOL;
		$file .= '    public function search()'.PHP_EOL;
		$file .= '    {'.PHP_EOL;
		$file .= '        $request = $this->getRequest();'.PHP_EOL;
		$file .= $search; 
		$file .= '        return $this;'.PHP_EOL;
		$file .= '    }'.PHP_EOL;
		$file .= ''.PHP_EOL;
		$file .= '    public function getDataGrid($fromRecord = null, $countRecord = null)'.PHP_EOL;
		$file .= '    {'.PHP_EOL;
		$file .= '        $dataList = $this->findAll($fromRecord, $countRecord);'.PHP_EOL;
		$file .= '        $dataGrid = new \Spawn\View\Helper\DataGrid();'.PHP_EOL;
		$file .= '        $dataGrid->top(array(\''.$struct.'\', \'Options\'));'.PHP_EOL;
		$file .= '        $dataGrid->rows($dataList, array(\''.$struct.'\', array(\'view\', \'edit\', \'delete\')));'.PHP_EOL;
		$file .= '        return $dataGrid;'.PHP_EOL;
		$file .= '    }'.PHP_EOL;
		$file .= '}';
		
		//create orm file
		$name = str_replace( '_', DIRECTORY_SEPARATOR, $name);
		$fileName = ROOT_PATH.'Application' . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Orm' . DIRECTORY_SEPARATOR . $name . '.php';
		file_put_contents($fileName, $file);
		chmod($fileName, 0755);
	}
	
	
	/**
	*clear array with non exists keys in table structure 
	*
	*@param - array - values
	*@return - array	
	*/
	private function deleteUnusedValues(array $values)
	{
		if($this -> _structure){
			foreach($values as $key => $val){
				if(!in_array($key, $this -> _structure)){
					unset($values[ $key ]);
				}			
			}
		}
		return $values;
	}

        /**
         *
         * @param string $name
         * @return Orm
         */
        public function filter($name)
        {
            $this -> _filter[] = $name;
            return $this;
        }

        protected function _useFilter($data)
        {
            foreach($this -> _filter as $key){
                $name = '_filter'.$key;
                if(!method_exists($this, $name)){
                    throw new OrmException('Filter '.$name.' not found!');
                }
                if(is_array($data) && isset($data[0])){
                    $result = array();
                    foreach($data as $key){
                        $result[] = $this -> $name($key);
                    }
                    $data = $result;
                }else{							
                    $data = $this -> $name($data);
                }
            }
            return $data;
        }
		
		/**
		* Default filter 
		* @param array|object $data
		* @return array|object
		*/
		protected function _filterDefault($data)
		{  
			return $data;
		}
	
}//orm

class OrmException extends \Exception {}
