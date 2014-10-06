<?php
/**
 * Spawn Framework
 *
 * DataGrid
 *
 * @author  Paweł Makowski
 * @copyright (c) 2010-2014 Paweł Makowski
 * @license http://spawnframework.com/license New BSD License
 * @package Helper
 */
/**
 * @Demo
$t = new Helped\DataGrid('name');

$t -> setAction('download', function($act, $id){
return '<a href="'.$act.'?download='.$id.'">Download</a>';
});

$t -> top(array('Id', 'Title', 'User', 'Add Date', 'Options'));
$t -> rows($values, array(
'id',
'title',
array('author_id', function($id){
return \Spawn\Orm::factory('user')->find($id)->name;
}),
'add_date',
array('view', 'update', 'delete'),
));
echo $t -> render();
 */
namespace Spawn\View\Helper;
use \Spawn\Arr;

class DataGrid
{
    /**
     * @var array
     */
    protected $_action = array();

    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     * @var array
     */
    protected $_rows;
    
    /**
     * @var array
     */
    protected $_headers;

    /**
     * @var string
     */
    protected $_url;


    /**
     * row to render
     *
     * @var object
     */
    public $row;
    
    /**
     * @var bool|array
     */
    protected $_search = false;

    /**
     * @var string
     */
    protected $_name;
    
    /**
    * @var integer
    */
    protected $_count;


    /**
     * add default row actions - [view, update, delete]
     */
    public function __construct($name = 'default')
    {    	
        $this->_name = $name;
        $this->_action['view'] = function($act, $id) { return '<a href="'.\Spawn\Url::site($act.'/view/'.$id).'" class="view btn btn-link"><span class="glyphicon glyphicon-search"></span></a>'; };
        $this->_action['edit'] = function($act, $id) { return '<a href="'.\Spawn\Url::site($act.'/edit/'.$id).'" class="edit btn btn-link"><span class="glyphicon glyphicon-pencil"></span></a>'; };
        $this->_action['delete'] = function($act, $id) { return '<a href="'.\Spawn\Url::site($act.'/delete/'.$id).'" class="delete btn btn-link"><span class="glyphicon glyphicon-trash"></span></a>'; };
        $this->_action['up'] = function($act, $id) { return '<a href="'.\Spawn\Url::site($act.'/up/'.$id).'" class="up btn btn-link"><span class="glyphicon glyphicon-arrow-up"></span></a>'; };
        $this->_action['down'] = function($act, $id) { return '<a href="'.\Spawn\Url::site($act.'/down/'.$id).'" class="down btn btn-link"><span class="glyphicon glyphicon-arrow-down"></span></a>'; };        
    }


    /**
     * table head row
     * @param array $top
     * @return self
     */
    public function top(array $dataList, array $search = null)
    {
        $orderData = \Spawn\Session::load()->get('order_'.$this->_name);
        $str = '';

        if(!\Spawn\Arr::isAssoc($dataList)) {
            $dataList = array_combine(array_values($dataList), array_values($dataList));
        }

        foreach($dataList as $key => $val) {
            $order = (isset($orderData[$key]) && $orderData[$key] != 'DESC')? 'DESC': 'ASC';

            $clear = key(array_reverse($dataList));
            if($key === $clear) {
                $order = 'CLEAR';
            }
			
			$this->_headers[] = array(
				'key'=>$key,
				'value'=>$val,
				'order'=>$order
			);
        }

        if(null != $search) {
            $this->loadSearch($search);
        }
        
        return $this;
    }

    public function loadSearch(array $rows)
    {
        $this->_search = array();

        $form = new \Spawn\Form();
        $request = new \Spawn\Request();

        $i=1;
        foreach($rows as $row) {

            if(is_callable($row)) {
                $data = $row();
                $this->_search[] = array('input'=>$data);
            }else {
            	$this->_search[] = array('row'=>$row, 'value'=>$request->post($row));		
            }
            $i++;
        }
    }
    
    /**
    * @param string $name
    * @return string
    */
    protected function _getRowParam($name)
    {
    	if($this->row instanceof \StdClass){
    		return $this->row->{$name};
    	}
    	return $this->row[$name];
    }

    /**
     * table rows
     * @param array $values
     * @param string $info
     * @return self
     */
    public function rows($values, $info)
    {    
    	$this->_count = count($values);
        $rows='';
        $pri=null;
        $i=0;
        foreach($values as $data){
            $this->row = $data;
            $row = array();
            $pri = $this->_getRowParam($this->getPrimary());
            foreach($info as $key){
                if( !is_array($key) ){
                    $row[] = \Spawn\Filter::xss($this->_getRowParam($key));
                }elseif( isset($key[1]) && is_callable($key[1]) ){
                    $row[] = $key[1]($this->_getRowParam($key[0]), $data);
                }else{
                    $str = '';
                    foreach($key as $use ){
                        $str .= $this->_action[$use]($this->getUrl(), $pri, $data);
                    }
                    $row[] = $str;
                }
            }

            $str = '';
            $j = 1;
            foreach($row as $key){
                $this->_rows[$i][$j] = $key;
                $j++;
            }
            $i++;
        }
        return $this;
    }

    /**
     * @param string
     * @return self
     */
    public function setPrimary($name)
    {
        $this->_primary = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrimary()
    {
        return $this->_primary;
    }

    /**
     * @param string
     * @return self
     */
    public function setUrl($data)
    {
        $this->_url = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if(null == $this->_url){
            $uri = new \Spawn\Request\Uri;
            $this->_url = $uri->param(0);
        }
        return $this->_url;
    }
    
    public function count()
    {
    	return $this->_count;	
    }

    /**
     * @return string
     */
    public function render($tpl = 'default')
    {
    	$di = new \Spawn\DI;
    
        $view = new \Spawn\View('_Datagrid/'.$tpl);
        $view->form = $di->form;
        $view->headers = $this->_headers;
        $view->search = $this->_search;
        $view->rows = $this->_rows;
        $str = $view->render();
        return $str;
    }

    /**
     * set new row action
     * @param string $name
     * @param function $value
     */
    public function setAction($name, $value)
    {
        $this->_action[$name] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}

