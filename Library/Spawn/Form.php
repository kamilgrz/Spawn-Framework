<?php
/**
* Spawn Framework
*
* Class to create form html tags
*
* @author  Paweł Makowski
* @copyright (c) 2010-2013 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Form
*/
namespace Spawn;
class Form 
{
	/**
	* true if we use field tag
	* @var bool
	*/
	protected $_field = false;
			
	/**
	* @var string
	*/
	protected $_rowStyle = null;
	
	/**
	* @var string
	*/
	protected $_errorStyle = null;
	
	/**
	* @var array
	*/
	protected $_toErrorArray = array();
	
	/**
	* @var string 
	*/
	protected $_rowData = '<div class="control-group"><label class="control-label">{Label}<sup>{Required}</sup></label><div class="controls">{Input} {About}</div></div>';
    /**
	* @var string
	*/ 
	protected $_rowDataError = '<div class="control-group error"><label class="control-label ">{Label}<sup>{Required}</sup></label><div class="controls">{Input} {About}</div></div>';
    /**
     * @var string
     */
    protected $_boxLabel = '<label class="checkbox">{Data}</label>';
    /**
     * @var string
     */
    protected $_radioLabel = '<label class="radio">{Data}</label>';
    
	/**
	* open form tag 
	*
	* @param string $action 
	* @param string $method - post | get
	* @param array $params
	* @return string
	*/
	public function open($action = '', $method = 'post', $params=null)
	{
		//array('enctype'=>'multipart/form-data')
		$params = (null != $params)? $this -> _params($params) : '';
		return '<form action="'.$action.'" method="'.$method.'" '.$params.'>'.PHP_EOL;
	}
	
	/**
	* declare tpl to replace
	*
	* @param array $data
	* @return $this
	*/
	public function setTpl(array $data)
	{
		$this -> _tpl = $data;
		return $this;
	}
	
	/**
	* create params to input
	* 
	* @param array $param
	* @return string
	*/
	protected function _params(array $param)
	{
		$params='';
		foreach($param as $key => $val){
			$params.= $key.'="'.Filter::xss($val).'" ';
		}
		return $params;
	}
	
	/**
	* open fieldset tag and legend tag 
	*
	* @param array $params
	* @return string
	*/
	public function fieldset($params = null)
	{		
         	$this -> _field = true;
         	$params = (null != $params)? $this -> _params($params) : '';
         	$field = '<fieldset '.$params.'>'.PHP_EOL;
         	return $field;         	
	}	
	
	/**
	* create legend tag 
	*
	* @param string $val
	* @param array $params
	* @return string
	*/
	public function legend($val, $params = null)
	{
		$params = (null != $params)? $this -> _params($params) : '';
         	return '<legend '.$params.'>'.$val.'</legend>'.PHP_EOL;
	}
	
	/**
	* create intup type button 
	*
	* @param string $name
	* @param string $value
	* @param array $params
	* @return string
	*/
	public function button($name, $value, $params = array('class'=>'btn') )
	{
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="button" name="'.$name.'" value="'.Filter::xss($value).'" '.$params.'>';
	}
	
	/**
	* create intup type text
	*
	* @param string $name
	* @param string $value
	* @param array $parms
	* @param bool $filler
	* @return string
	*/
	public function text($name, $value = '', $params = null, $filler = false)
	{
		$filler = (true == $filler)? $this -> filler($value) : '';
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="text" name="'.$name.'" value="'.Filter::xss($value).'" '.$params.' '.$filler.'>';
	}
	
	/**
	* create intup type hidden
	*
	* @param string $name
	* @param string $value
	* @param array $params
	* @return string
	*/
	public function hidden($name, $value = '', $params = null)
	{
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="hidden" name="'.$name.'" value="'.Filter::xss($value).'" '.$params.'>';
	}
	
	/**
	* create intup type image
	*
	* @param string $name
	* @param string $src
	* @param string $value
	* @param array $params
	* @return string
	*/
	public function image($name, $src, $params = null)
	{
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="image" src="'.Filter::xss($src).'" name="'.$name.'" '.$params.'>';
	}
	
	/**
	* create intup type password
	*
	* @param string $name
	* @param string $value
	* @param array $params
	* @param bool $filler
	* @return string
	*/
	public function password($name, $value = '', $params = null, $filler = false)
	{
		$filler = (true == $filler)? $this -> filler($value) : '';
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="password" name="'.$name.'" value="'.Filter::xss($value).'" '.$params.' '.$filler.'>';
	}
	
	/**
	* create intup type url
	*
	* @param string $name
	* @param string $value
	* @param array $params
	* @param bool $filler
	* @return string
	*/
	public function url($name, $value = '', $params = null, $filler = false)
	{
		$filler = (true == $filler)? $this -> filler($value) : '';
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="url" name="'.$name.'" value="'.Filter::xss($value).'" '.$params.' '.$filler.'>';
	}
	
	/**
	* create intup type email
	*
	* @param string $name
	* @param string $value
	* @param array $params
	* @param bool $filler
	* @return string
	*/
	public function email($name, $value = '', $params = null, $filler = false)
	{
		$filler = (true == $filler)? $this -> filler($value) : '';
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="email" name="'.$name.'" value="'.Filter::xss($value).'" '.$params.' '.$filler.'>';
	}
	
	/**
	* create intup type radio
	*
	* @param string $name
	* @param string $value
	* @param bool $check 
	* @param array $params
	* @return string
	*/
	public function radio($name, $value, $check = false, $params = null)
	{
		$check = (false == $check)? '' : ' checked="checked" ';
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="radio" name="'.$name.'" value="'.Filter::xss($value).'" '.$params.' '.$check.'>';
	}
	
	/**
	* create array to radioList/chceckboxList
	* @param array $model Orm->findAll() 
	* @param string $key Id
	* @param string $val Name
	* @return array
	*/
	public function toList($model, $key = 'Id', $val = 'Name')
	{
		$arr = array();
		foreach($model as $row){
			$arr[$row->$key] = ($val != false)? $row->$val : $row->$key;
		}
		return $arr;
	}
	
	/**
	* create checkbox list
	* @param array $values self::radio values 
	* @param string $sep
	* @return string
	*/
	public function radioList($name, array $values, $check = null, $sep = ' ')
	{
		$str = '';		
		foreach($values as $key => $val){
			$checked = false;
			if($check == $key){
				$checked = true;
			}
            $radio = $this->radio($name, $key, $checked).' '.$val . $sep . PHP_EOL;
            $str .= str_replace('{Data}', $radio, $this->_radioLabel);
		}
		return $str;
	}
	
	/**
	* create intup type checkbox
	*
	* @param string $name
	* @param string $value
	* @param bool $check 
	* @param array $params
	* @return string
	*/
	public function checkbox($name, $value, $check = false, $params = null)
	{
		$check = ($check == false)? '' : ' checked="checked" ';
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="checkbox" name="'.$name.'" value="'.Filter::xss($value).'" '.$params.' '.$check.'>';
	}
	
	/**
	* create checkbox list
	* @param array $values self::checkbox values 
	* @param string $sep
	* @return string
	*/
	public function boxList($name, array $values, array $check = array(), $sep = ' ')
	{
		$str = '';		
		foreach($values as $key => $val){
			$checked = false;
			if(in_array($key, $check)){
				$checked = true;
			}
			$box = $this->checkbox($name.'[]', $key, $checked).' '.$val . $sep . PHP_EOL;
            $str .= str_replace('{Data}', $box, $this->_boxLabel);
		}

		return $str;
	}
	
	/**
	* open select tag
	*
	* @param string $name
	* @param array $params
	* @return string
	*/
	public function selectOpen($name, $params = null)
	{
		$params = (null != $params)? $this -> _params($params) : '';
		return '<select name="'.$name.'"  '.$params.'>'.PHP_EOL;
	}
	
	/**
	* close select tag
	* 
	* @return string
	*/
	public function selectClose()
	{
		return '</select>'.PHP_EOL;
	}
	
	/**
	* create intup type select + options
	*
	* @param string $name
	* @param array $options
	* @param string $selected
	* @param array $params
	* @return string
	*/
	public function select($name, array $option = array(), $selected = null,  $params = null)
	{
		$options = $this -> options($option, $selected);
		$params = (null != $params)? $this -> _params($params) : '';
		return '<select name="'.$name.'"  '.$params.'>'.$options.'</select>'.PHP_EOL;
	}
	
	/**
	* create intup type select + options
	*
	* @param string $name
	* @param array $options
	* @param array $params
	* @return string
	*/
	public function datalist($name, array $option = array(), $params = null)
	{
		$options = $this -> options($option);
		$params = (null != $params)? $this -> _params($params) : '';
		return '<datalist id="'.Filter::xss($name).'"  '.$params.'>'.$options.'</datalist>'.PHP_EOL;
	}
	
	/**
	* create option tag
	*
	* @param string $name
	* @param string $val
	* @param bool $select
	* @return string
	*/
	public function option($key, $val = null, $select = false)
	{
		$selected = ($select != $key)? '' : 'selected="selected"';
		$val = (null == $val)? $key: $val;
		return '<option value="'.Filter::xss($key).'" '.$selected.'>'.Filter::xss($val).'</option>'.PHP_EOL;
	}
	
	/**
	* create option tags
	*
	* @param array $options
	* @param string $select
	* @return string
	*/
	public function options($options, $select = null)
	{
		$opt = '';
		foreach($options as $key => $val){			
			$opt .= $this -> option($key, $val, $select);
		}		
		return $opt;		
	}
	
	/**
	* create intup type textarea
	*
	* @param string $name
	* @param string $val
	* @param array $params
	* @param string $filler
	* @return string
	*/
	public function textarea($name, $val = '', $params = null, $filler = false)
	{
		$filler = (true == $filler)? $this -> filler($val) : '';
		$params = (null != $params)? $this -> _params($params) : '';
		return '<textarea name="'.$name.'" '.$params.' '.$filler.'>'.Filter::xss($val).'</textarea>';
	}
	
	/**
	* create intup type file
	*
	* @param string $name
	* @param array $params
	* @return string
	*/
	public function file($name, $params = null)
	{
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="file" name="'.$name.'" '.$params.'>';
	}
	
	/**
	* create intup type submit
	*
	* @param string $value
	* @param array $params
	* @param string $name
	* @return string
	*/
	public function submit($value = 'OK', $name = 'submit' , $params = array('class'=>'btn btn-primary'))
	{
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="submit" name="'.$name.'" value="'.Filter::xss($value).'" '.$params.'>';
	}
	
	/**
	* create intup type reset
	*
	* @param string $name
	* @param array $params
	* @return string
	*/
	public function reset($value = 'Reset', $params = array('class'=>'btn') )
	{
		$params = (null != $params)? $this -> _params($params) : '';
		return '<input type="reset" name="reset" value="'.Filter::xss($value).'" '.$params.'>';
	}
	 
	/**
	* create tags to close fieldset, form and if param is not false create submit input 
	*
	* @param string $submit
	* @param array $params
	* @return string
	*/ 
	public function close($submit = false, $params = null)
	{
		$close = (false == $submit)? '' : $this->row('&nbsp;',$this->submit($submit, $params));
		$close .= (true == $this -> _field)? '</fieldset></form>' : '</form>';
		return $close.PHP_EOL;
	}
	
		
	/**
	* create tags 'row' 
	*
	* @param array $data
	* @param bool $error
	* @return string
	*/ 
	public function row($label, $input, $about = '', $required = '',$error = false)
	{
		$rowData = (true == $error)? $this -> _rowDataError : $this -> _rowData;
		$row = str_replace(
			array('{Label}','{Input}','{About}','{Required}'), 
			array($label, $input, $about, $required),
			$rowData
			) . PHP_EOL;
		
		return $row;
	}
	
	/**
	* declare new row() template
	*
	* @param string $data
	* @return $this
	*/
	public function setRowTpl($data)
	{
		$this -> _rowData = $data;
		return $this;
	}
	
	/**
	* @return string
	*/
	public function getRowTpl()
	{
		return $this -> _rowData;
	}
	
	/**
	* declare new row() error template
	*
	* @param string $data
	* @return $this
	*/
	public function setRowErrorTpl($data)
	{
		$this -> _rowDataError = $data;
		return $this;
	}
	
	/**
	* @return string
	*/
	public function getRowErrorTpl()
	{
		return $this -> _rowDataError;
	}
	
	/**
	* create filler to inputs
	*
	* @param string $val
	* @return string
	*/ 
	public function filler($val)
	{
		return 'onClick="if(this.value == \''.$val.'\') this.value = \'\';" onBlur="if(this.value == \'\') this.value= \''.$val.'\';"';
	}
	
	/**
	* get input names to error raw
	*
	* @param array $name
	*/ 
	public function toError($name)
	{
		foreach($name as $key){
			$this->_toErrorArray[] = $key;
		}
		return $this;
	}
	
	/**
	* create error message
	*
	* @param string $lang PL
	* @param string $class ul class
	* @return string
	*/
	public function getErrorMessage($lang = 'PL', $class = 'error')
	{
		$data = new \Spawn\Data('FormError');
		$dataList = $data->get($lang);
		$str = '<ul class="'.$class.'">';
		foreach($this->_toErrorArray as $key){
			$str .= '<li>'.$dataList[$key].'</li>';
		}		
		$str .= '</ul>';
		return $str;
	}
	
	/**
	* create inputs in 'row' tags
	*
	* @param array $input
	* @return string
	*/ 
	public function create($input)
	{
		$form = '';
		//create inputs
		foreach($input as $key => $val){	
			//switch input type and create it		
			switch($val['type']){
				case 'text': 
					$val = Arr::update($val, array('name', 'value', 'params', 'filler'), '');
					$inp = $this -> text($val['name'], $val['value'], $val['params'], $val['filler']);
				break;
				
				case 'email': 
					$val = Arr::update($val, array('name', 'value', 'params', 'filler'), '');
					$inp = $this -> email($val['name'], $val['value'], $val['params'], $val['filler']);
				break;
				
				case 'url': 
					$val = Arr::update($val, array('name', 'value', 'params', 'filler'), '');
					$inp = $this -> url($val['name'], $val['value'], $val['params'], $val['filler']);
				break;
				
				case 'pass': 
				case 'password':
					$val = Arr::update($val, array('name', 'value', 'params', 'filler'), '');
					$inp = $this -> password($val['name'], $val['value'], $val['params'], $val['filler']);
				break;
				
				case 'textarea': 
					$val = Arr::update($val, array('name', 'value', 'params', 'filler'), '');
					$inp = $this -> textarea($val['name'], $val['value'], $val['params'], $val['filler']);
				break;
				
				case 'button': 
					$val = Arr::update($val, array('name', 'value', 'css'), '');
					$inp = $this -> button($val['name'], $val['value'], $val['params']);
				break;
				case 'hidden': 
					$inp = $this -> hidden($val['name'], $val['value']);
				break;
				case 'image': 
					$val = Arr::update($val, array( 'name', 'value', 'params','src'), '');
					$inp = $this -> image($val['name'], $val['src'], $val['value'], $val['params']);
				break;
				case 'select': 
					$val = Arr::update($val, array( 'name', 'params', 'selected'), '');
					if(!isset($val['option']) OR !is_array($val['option'])) $val['option']=array();
					$inp = $this -> select($val['name'], $val['params'], $val['selected'], $val['option']);
				break;
				
				case 'datalist': 
					if(!isset($val['option']) OR !is_array($val['option'])) $val['option']=array();
					$inp = $this -> datalist($val['name'], $val['option']);
				break;
				
				case 'submit':
					$val['name'] = ( isset($val['name']) )? $val['name'] : 'Submit';
					$val = Arr::update($val, array('params'), '');
					$inp = $this -> submit($val['value'], $val['name'], $val['params']);
				break;
				case 'reset':
					$inp = $this -> reset($val['value'], $val['params']);
				break;
				case 'radio':
					$val = Arr::update($val, array('name', 'value', 'params',  'checked'), '');
					$inp = $this -> radio($val['name'], $val['value'], $val['checked'] ,$val['params'] );
				break;
				case 'checkbox':
					$val = Arr::update($val, array('name', 'value', 'params', 'checked'), '');
					$inp = $this -> checkbox($val['name'], $val['value'],$val['checked'] , $val['params']);
				break;
				case 'boxList':
                    $val['sep'] = ( isset($val['sep']) )? $val['sep'] : '';
                    $val['checked'] = ( isset($val['checked']) )? $val['checked'] : array();
					$inp = $this -> boxList($val['name'], $val['values'], $val['checked'], $val['sep']);
				break;
				case 'radioList':
					$val = Arr::update($val, array('checked', 'sep'), '');
					$inp = $this -> radioList($val['name'], $val['values'], $val['checked'], $val['sep']);
				break;
				case 'file':
					$val = Arr::update($val, array('css', 'params'), '');
					$inp = $this -> file($val['name'], $val['params']);
				break;
				case 'other':
					$inp = $val['data'];	
				break;
			}
			$val = Arr::update($val, array('error', 'about'), '');
			if(isset($val['name']) AND in_array($val['name'], $this -> _toErrorArray)) $val['error'] = 1;
			
			//create input
			$req = array_key_exists('required', $val)? '*' : '';
			$form .= ( !in_array($val['type'], array('hidden', 'datalist') ) )? $this -> row( $key, $inp, $val['about'] , $req, $val['error']) : $inp;
		}
		return $form;
	}	
		
}//form


