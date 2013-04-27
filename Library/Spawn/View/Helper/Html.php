<?php
/**
* Spawn Framework
*
* html
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Helper
*/
namespace Spawn\View\Helper;

use \Spawn\Config as Config;

class Html
{
    /**
     * @param string $src
     * @param array $params
     * @return string
     */
    public function img($src, array $params = array())
    {
        $str = '<img src="';
        if(strpos($src,'http')===0) {
            $str .= $src;
        }else {
            $str .= Config::load('Uri') -> get('base').'Media/Images/'.$src;
        }
        $str .= '" ';
        foreach($params as $key => $val) {
            $str .= $key.'="'.$val.'" ';
        }
        $str .= '/>';
        return $str;
    }
    
    /**
     * @param string $src
     * @param array $params
     * @return string
     */
    public function href($href, $name=null, array $params = array())
    {
        $name = ($name != null)? $name : $href;
        $str = '<a href="';
        if(strpos($href, 'http')===0) {
            $str .= $href;
        }else {
            $str .= Config::load('Uri') -> get('base').$href;
        }
        $str .= '" ';
        foreach($params as $key => $val) {
            $str .= $key.'="'.$val.'" ';
        }
        $str .= '>'.$name.'</a>';
        return $str;
    }

	/**
         *
         * @param string $val
         * @param string $title
         * @return string
         */
	public function rss($val, $title)
	{
		return '<link rel="alternate" type="application/rss+xml" title="'.$title.'" href="'.$val.'" />'.PHP_EOL;
	}
	
	/**
         *
         * @param string $val
         * @return string
         */
	public function icon($val)
	{
		return '<link rel="shortcut icon" href="'.$val.'" />'.PHP_EOL;
	}
	
	/**
         *
         * @param string|array $css
         * @return string
         */
	public function css($css)
	{
		if(is_array($css)){
			$val = '';
			foreach($css as $key){
				$val .= '<link rel="Stylesheet" href="'.$key.'" type="text/css" />'.PHP_EOL;
			}
		}else{
			$val = '<link rel="Stylesheet" href="'.$css.'" type="text/css" />'.PHP_EOL;
		}
		return $val;		
	}
	
	/**
	*create tags type="text/javascript"
	*
	*@param string|array $js file + path
	*@return string
	*/
	public function js($js)
	{
		if(is_array($js)){
			$val = '';
			foreach($js as $key){
				$val .= '<script type="text/javascript" src="'.$key.'"></script>'.PHP_EOL;
			}
		}else{
			$val = '<script type="text/javascript" src="'.$js.'"></script>'.PHP_EOL;
		}
		return $val;		
	}
	/**
         *
         * @param integer $time
         * @return string
         */
	public function refresh($time = 5)
	{
		return '<meta http-equiv="Refresh" content="'.$time.'" />'.PHP_EOL;
	}

	/**
         *
         * @param string $www
         * @param integer $time
         * @return string
         */
	public function redirect($www, $time = 5)
	{
		return '<meta http-equiv="Refresh" content="'.$time.';url='.$www.'" />'.PHP_EOL;
	}
	
	/**
         *
         * @param string $val
         * @return string
         */
	public function charset($val = 'utf-8')
	{
		return '<meta charset="'.$val.'" />'.PHP_EOL;
	}
	
	/**
         *
         * @param string $val
         * @return string
         */
	public function key($val)
	{
		return '<meta name="Keywords" content="'.$val.'" />'.PHP_EOL;
	}
	
	/**
	*create meta desc tag
	*
	*@param string $val
	*@return string
	*/
	public function desc($val)
	{
		return '<meta name="Description" content="'.$val.'" />'.PHP_EOL;
	}
	
	/**
	*create meta author tag
	*
	*@param string $val
	*@return string
	*/
	public function author($val)
	{
		return '<meta name="Author" content="'.$val.'" />'.PHP_EOL;
	}	

	/**
	*print_r in pre
	*
	*@param array $val
        *@return string
	*/
	public function pre($val)
	{
       		$str = '<pre>';
       		$str .= print_r($val, true);
        	$str .='</pre>';
        	return $str;
   	}

        /**
         *
         * @param string $str
         * @param integer $num
         * @param string $more
         * @return string
         */
   	public function cropText($str, $num, $more = '...')
	{
		if( strlen($str) > $num ) {
			$str = mb_substr($str, 0, $num) . $more;
		}
		return $str;
	}


}//html

