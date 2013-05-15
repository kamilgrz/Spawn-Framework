<?php
/**
* Spawn Framework
*
* View
*
* @author  Paweł Makowski
* @copyright (c) 2010-2013 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package View
*/
namespace Spawn;
use \Spawn\View\Helper;


class View 
{
	/**
         * args for base page
         * @var array
         */
	protected $_values = array();
	
	/**
         * @var string
         */
	protected $_page = null;
	
	/**
         * file name
         * @var string
         */
	protected $_name = null;
	
	/**
         * @var \Spawn\View\Tpl
         */
	protected $_tpl = null;
	
	/**
         * path to compiled view files
         * @var string
         */
	protected $_path = 'Application/View/';
		
		
	/**
	*set new value to view params
	*
	*@param string $name
	*@param mixed $value
	*/
	public function __set($name, $value)
	{
		$this -> _values[ $name ] = $value;
	}
	
	/**
	*get view params
	*
	*@param string $name
	*/
	public function __get($name)
	{
		return $this -> _values[ $name ];
	}
	
	/**
	*declare view file
	*
	*@param string $name file name
	*@param string $type
	*/
	public function __Construct($name = null, $type = '.phtml')
	{
	    $this -> _name = $name;
	     if(null == $this -> _name){
	        $uri = new Request\Uri;
	        $controller = str_replace(' ', '\\',ucwords(str_replace(Spawn::$controllerSeparator, ' ', $uri -> param(0))));
		    $this -> _name = str_replace( 
					array('_', Spawn::$controllerSeparator, '\\'), 
					DIRECTORY_SEPARATOR,
					ucfirst($controller) . DIRECTORY_SEPARATOR . $uri -> param(1)
				);
		}	

        $this->loadDIHelpers();
		$this -> _page = $this -> _name.$type;
	}

    public function loadDIHelpers()
    {
        $di = new DI;
        if(!$di->has('datagrid')) $di->set('datagrid', '\Spawn\View\Helper\DataGrid');
        if(!$di->has('gravatar')) $di->set('gravatar', '\Spawn\View\Helper\Gravatar');
        if(!$di->has('html')) $di->set('html', '\Spawn\View\Helper\Html');
        if(!$di->has('menu')) $di->set('menu', '\Spawn\View\Helper\Menu');
        if(!$di->has('assetsLoader')) $di->set('assetsLoader', '\Spawn\View\Helper\AssetsLoader');
        if(!$di->has('table')) $di->set('table', '\Spawn\View\Helper\Table');
        if(!$di->has('alert')) $di->set('alert', '\Spawn\View\Helper\Alert');
        if(!$di->has('form')) $di->set('form', '\Spawn\Form');
        if(!$di->has('url')) $di->set('url', '\Spawn\Url');

        $this -> _values['di'] = $di;
    }
	
	/**
	*use sf_tpl method if sf_view havent 
	*
	*@throw exception if method not exists
	*@param string $method
	*@param array $args
	*/
	public function __call($method, array $args)
	{
		//method isset in sf_tpl?
		if( !method_exists($this->_tpl, $method) ) throw new ViewException('Method "'.$method.'" not found!');
			
		//use method
		$return = call_user_func_array(array($this->_tpl, $method), $args);
		return ($return instanceof View\Tpl) ? $this : $return;		
	}
	
	/**
	* set path to files
	* 
	* @param string $path
	* @return $this
	*/
	public function setPath($path)
	{
		$this->_path = $path;
		return $this;
	}
	
	/**
	* @return string
	*/
	public function getPath()
	{
		return $this->_path;
	}
	
	/**
	*declare sf_tpl instance
	*modyfication $this->_path if $viewPath = false
	*
	*@param bool $viewPath
	*@param string $tplClass
	*@return $this
	*/
	public function isTpl($viewPath = false, $tplClass = '\Spawn\View\Tpl')
	{
		$this -> _tpl = new $tplClass($this -> _name);
		if(true == $viewPath){
			$this -> _tpl -> setCompilePath( $this -> _path );
		}else{
			$this -> _path = $this -> _tpl -> getCompilePath();
		}

		return $this;
	}
	
	
	/**
	*replace template file name
	*
	*@param string $name
	*@param string $type
	*@return $this
	*/
	public function replace($name = null, $type = '.phtml')
	{
		$this -> _page = $name.$type;
		$this -> _name = $name;
		return $this;
	}
	
	/**
	*render view
	*
	*@return string
	*/
	public function __toString()
	{
		try{
			$data = $this -> render();
		}catch(ViewException $e){
		    $data = $e -> getMessage();
		}catch(\Exception $e){
			$data = $e -> getMessage();
		}	
		return $data;
	}
	
	
	/**
	*add value
	*
	*@param string|array assoc $values
	*@param string $val
	*@return $this
	*/
	public function assign($values, $val = null)
	{
		if( is_array($values) ){
			foreach($values as $key => $val){
				$this -> _values[ $key ] = $val;
			}
		}else{
			$this -> _values[ $values ] = $val;
		}
		return $this;
	}
	
	/**
	* add helper to DI
	*
	* @param string $name
	* @param mixed $val
	* @return $this
	*/
	public function setHelper($name, $val)
	{   
	    $this -> _values['di']->set($name, $val);
	    return $this;
	}
	
	/**
	*declare view file
	*
	*@param string $name
	*@param string $type
	*@return object
	*/	
	public static function load($name, $type = '.phtml')
	{
		return new View($name, $type);
	}
	
	/**
	*remove all values
	*
	*@return $this
	*/
	public function clearValues()
	{
		$this -> _values = array();
		return $this;
	}
		
	/**
	* @param array $values values to self::assign()
    * @return string
    */
	public function render(array $values = array())
	{
		$this -> assign($values);
		ob_start();		
		
		extract($this -> _values);
				
		include(ROOT_PATH . $this -> _path . $this -> _page);
		
		$page = ob_get_contents();
		ob_end_clean();
		
		return $page;
	}	

}//View

class ViewException extends \Exception {}
