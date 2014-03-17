<?php
/**
 * Spawn Framework
 *
 * DataGrid
 *
 * @author  Paweł Makowski
 * @copyright (c) 2010-2013 Paweł Makowski
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
     * @var string
     */
    protected $_rows;

    /**
     * @var string
     */
    protected $_head;

    /**
     * @var string
     */
    protected $_url;

    /**
     * @var Table
     */
    public $table;

    /**
     * row to render
     *
     * @var object
     */
    public $row;

    /**
     * @var bool
     */
    protected $_search = false;

    /**
     * @var string
     */
    protected $_name;


    /**
     * add default row actions - [view, update, delete]
     */
    public function __construct($name = 'default')
    {
        $this->_name = $name;
        $this->_action['view'] = function($act, $id) { return '<a href="'.\Spawn\Url::site($act.'/view/'.$id).'" class="view btn btn-link">View</a>'; };
        $this->_action['edit'] = function($act, $id) { return '<a href="'.\Spawn\Url::site($act.'/edit/'.$id).'" class="edit btn btn-link">Edit</a>'; };
        $this->_action['delete'] = function($act, $id) { return '<a href="'.\Spawn\Url::site($act.'/delete/'.$id).'" class="delete btn btn-link">Delete</a>'; };
        $this->table = new Table();
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

            $a = '<a href="?'.$key.'='.$order.'">'.$val.'</a>';
            $str .= '<th class="th_'.$key.'">'.$a.'</th>';
        }
        $this->_head = '<tr>'.$str.'</tr>';

        if(null != $search) {
            $this->_head .= $this->getSearch($search);
        }
        return $this;
    }

    public function getSearch(array $rows)
    {
        $this->_search = true;

        $str = '<tr class="tr_search">';
        $form = new \Spawn\Form();
        $request = new \Spawn\Request();

        $i=1;
        foreach($rows as $row) {

            if(is_callable($row)) {
                $data = $row();
            }else {
                $data = $form->text($row,$request->post($row), array('class'=>'form-control'));
            }

            $str .= '<th class="th_'.$i.'">'.$data.'</th>';
            $i++;
        }
        $str .= '<th>'.$form->submit('»').'</th>';

        $str .= '</tr>';

        return $str;
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
                    $row[] = $key[1]($this->_getRowParam($key[0]));
                }else{
                    $str = '';
                    foreach($key as $use ){
                        $str .= $this->_action[$use]($this->getUrl(), $pri);
                    }
                    $row[] = $str;
                }
            }

            $str = '';
            $j = 1;
            foreach($row as $key){
                $str .= '<td class="td_'.$j.'">'.$key.'</td>';
                $j++;
            }
            $rows .= '<tr class="tr_'.$i.'">'.$str.'</tr>'.PHP_EOL;
            $i++;
        }

        $this->_rows .= $rows;
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

    /**
     * @return string
     */
    public function render($class = 'table table-striped table-bordered table-hover table-condensed')
    {
        $str = $this->getTableOpen($class);
        $str .= $this->getHead();
        $str .= $this->getRows();
        $str .= $this->getTableClose();
        if($this->_search) {
            $str = '<form action="" method="post" class="form-search">'.$str.'</form>';
        }
        return $str;
    }

    /**
     * @param string $class
     * @return string
     */
    public function getTableOpen($class = 'table table-striped table-bordered table-hover table-condensed')
    {
        return '<table class="'.$class.'">';
    }
        
    /**
     * @param bool $thead
     * @return string
     */
    public function getHead($thead = true)
    {
        $str = (true == $thead)? '<thead>'.$this->_head.'</thead>'.PHP_EOL: $this->_head;
        return $str;
    }

    /**
     * @param bool $tbody
     * @return string
     */
    public function getRows($tbody = true)
    {
        $str = (true == $tbody)? '<tbody>'.$this->_rows.'</tbody>'.PHP_EOL: $this->_rows;
        return $str;
    }

    /**
     * @return string
     */
    public function getTableClose()
    {
        return '</table>';
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

