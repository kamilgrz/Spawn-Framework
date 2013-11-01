<?php
/**
* Spawn Framework
*
* Pager
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Pager 
{	
	/**
	* pager core configuration
	* @var array
	*/
	protected $config = array();
	
	/**
	* pager final config
	* @var array
	*/
	protected $cfg = array();	
	
	/**
	* @var string
	*/
	public $path = 'Application/View/_Pager/';
	
	/**
	* declare config , pages , view
	*
	* @param array $config
	*/
	public function __construct( array $config = array() )
	{
		
		$this -> config = Config::load('Pager') -> getAll();
		
		$params = array('type', 'name', 'total', 'limit', 'style');
		
		//actualisation config
		foreach($params as $key){
			if( isset($config[ $key ]) ){
				$this -> cfg[ $key ] = $config[ $key ];
			}else{
				$this -> cfg[ $key ] = $this -> config[ $key ];
			}
		}
		
		//declare total pages etc.
		$this -> cfg['total'] = ($this -> cfg['total'] < 1)? 1 : $this -> cfg['total'];
		$this -> cfg['pages'] = ceil($this -> cfg['total'] / $this -> cfg['limit']);
		if($this -> cfg['type'] == 'get'){
		    $req = new Request;
		    $this -> cfg['page'] = (int) $req -> get($this -> cfg['name'], 0);
		}else{
		    $uri = new Request\Uri;
		    $this -> cfg['page'] = (int) $uri -> param($this -> cfg['name'], 0);
		}		
		$this -> cfg['back'] = ($this -> cfg['page'] > 0)? true : false;
		$this -> cfg['next'] = ( $this -> cfg['page'] + 1 < $this -> cfg['pages'])? true : false;			
	}
	
	/**
	* @return integer
	*/
	public function getCursor()
	{
		return $this -> cfg['page'];
	}
	
	/**
	* @return integer
	*/
	public function count()
	{
		return $this -> cfg['pages'];
	}
	
	/**
	* @return bool
	*/
	public function isLast()
	{
		return (bool) $this -> cfg['next'] == false;
	}
	
	/**
	* @return bool
	*/
	public function isFirst()
	{
		return (bool) $this -> cfg['back'] == false;
	}
	
	/**
	* create core url to pager
	*/
	protected function _url()
	{
		if($this -> cfg['type'] != 'uri'){
			$this -> cfg['href'] = Url::get($this -> cfg['name'], '__PAGE__');
		}else{
			$this -> cfg['href'] = Url::uri('__PAGE__', $this -> cfg['name']);
		}		
	}
	
	/**
	* create pagination
	*
	* @return string
	*/
	public function render()
	{
		if($this -> cfg['pages'] < 2) return '';
		$this -> _url();
		ob_start();
			foreach($this -> cfg as $key => $val){		
				$$key = $val;
			}
			include(ROOT_PATH . $this->path .  $this -> cfg['style']);
			$pager = ob_get_contents();
		 ob_end_clean();
		 return $pager;	
	}
	
	/**
	* @return integer
	*/
	public function getOffset()
	{
		return ($this -> cfg['page'])*$this -> cfg['limit'];
	}
	
	/**
	* @return integer
	*/
	public function getLimit()
	{
		return $this -> cfg['limit'];	
	}
	
}//pager
