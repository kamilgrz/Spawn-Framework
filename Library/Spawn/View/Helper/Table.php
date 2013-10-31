<?php
/**
* Spawn Framework
*
* html table generator
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Helper
*/
namespace Spawn\View\Helper;
class Table
{

	/**
         * @var array
         */
	public $toUnset = array();	

	/**
	*write <table (.*)> (.*) </table>
	*
	* @param string $rows (<tr> (<td>(.*)</td>)+ </tr>)+
	* @param string $css <table> css/param
	* @return string
	*/
	public function table( $rows, $css = '' )
	{
      		if( is_array($rows) ){
       			$rows = implode(PHP_EOL, $rows);
        	}
		return '<table '.$css.'>'.$rows.'</table>'.PHP_EOL;
	}
	
	/**
	*open html table
	*
	*@param string $css <table> css/param
	*@return string 
	*/
	public function open( $css = '' )
	{
		return '<table '.$css.'>'.PHP_EOL;
	}
	
	/**
	*return close table tags
	*
	*@return string
	*/
	public function close()
	{
		return '</table>'.PHP_EOL;
	}	
	
	/**
	*write <td (.*)> (.*) </td>
	*
	*@param string $val <td>$val</td>
	*@param string $css td css
	*@return string (html tags)
	*/
	public function td( $val, $css = '', $sep = ', ' )
	{
		if(is_array($val)){
			$val = implode($sep, $val);
		}
		return '<td '.$css.'>'.$val.'</td>';
	}
	
	/**
	*write <tr (.*)> (.*) </tr>
	*@param string td html td tags
	*@param string $css - tr css
	*@return string (html tags)
	*/
	public function tr( $td, $css = '' )
	{
		return '<tr '.$css.'>'.$td.'</tr>'.PHP_EOL;
	}
	
	/**
	*declare param to unset with td
	*
	*@param array|string $val - if is array - array of delete params else one param to delete
	*@param++ string $++ if param 1 is string next params is param to delete
	*/
	public function toUnset( $val )
	{
		if( is_array($val) ){
			$this -> toUnset = array_merge($this -> toUnset, $val);
		}else{
			$numargs = func_num_args();
			$arg_list = func_get_args();
			for ($i = 0; $i < $numargs; $i++) {
				$this -> toUnset[] = $arg_list[$i];
			}
		}
	}
	
	/**
         * unset all params of toUnset (params to delete with td)
         */
	public function clearUnset()
	{
		$this -> toUnset = array();
	}
	
	/**
	*write <tr> (<td></td>)+ </tr>
	*
	*@param array $val - array with td params
	*@param string $tr_css - string - css to tr tag
	*@param array|string $td_css - array | string - css to td tag - if array - param 0 is (int) mod when use css - param 1 - (string) css
	*@param array $td_uniq - assoc array - [0] - td number , [1] - css to this td
	*@return string
	*/
	public function row( array $val, $tr_css = null, $td_css = null, array $td_uniq = null )
	{
		$td='';
		$i=1;
		$css='';
		
		//create td css
		$td_css = ( $td_css != null )?
				 ( ( is_array($td_css) )?
			 			 $td_css : array(1 => $td_css) ) : null;	
						 
		foreach($this -> toUnset as $unset){
            		if( isset($val[ $unset ]) ) unset( $val[ $unset ] );
            	}	 
            				 
		//write <td>	 			 		 			 
		foreach($val as $key){  
		
			//standard css   
			if(is_array($td_css)){   
				krsort($td_css);   
		        	foreach($td_css as $mod => $val ){
		        		if( ($i%$mod) == 0){
		        			$css = $val;
		        			break;
		        		}
		       		}
            		}else{
            			$css = $td_css;
            		}
            		
            		//uniq css
            		if( isset($td_uniq[ $i ]) ){     			
		        	$css = $td_uniq[ $i ];
		        }	
		        
		        //get td	       
			$td .= $this -> td($key, $css);
			$css = '';
			$i++;
		}
		$i = 1;
		return $this -> tr($td, $tr_css);
	}
	
	/**
	* write rows (<tr> (<td>(.*)</td>)+ </tr>)+
	*
	*@param array $values - assoc array - params to td
	*@param string $tr_css - string - css to tr tag
	*@param array|string $td_css - array | string - css to td tag - if array - param 0 is (int) mod when use css - param 1 - (string) css
	*@param array $tr_uniq - assoc array - [0] - tr number , [1] - css to this tr
	*@param array $td_uniq - assoc array - [0] - td number , [1] - css to this td
	*@return string 
	*/	
	public function rows( array $values, $tr_css = null, $td_css = null, $tr_uniq = null, $td_uniq = null )
	{
		$i = 1;
		$rows = '';
		$css = '';
		$td_css_new = null;
		$td_uniq_new = null;
		//create tr css
		$tr_css = ( $tr_css != null )?
				 ( ( is_array($tr_css) )? $tr_css : array(1=>$tr_css) ) 
				 		: null;		
				 			 			 
		foreach($values as $key){
		
			//standard css
			if(is_array($tr_css)){      
				krsort($tr_css);
		        	foreach($tr_css as $mod => $val ){ 
		        	
		        		//if $val is array : 1 param is tr css , 2 param td css  		
		        		$valCss = (is_array($val) )? $val[0] : $val;
		        		
		        		//select new css if modulo == 0		        			        		
		            		if( ($i%$mod) == 0){
		            			$css = $valCss; 
		            			$td_css_new = ( is_array($val) )? $val[1] : $td_css;
		            			break;
		            		}		            		
		       		}		       		
            }
            		
		       	//uniq css
		       	if( isset($tr_uniq[ $i ]) ){
		       	
		       			//tr css
		        		$valCss = (is_array($tr_uniq[$i]) )? $tr_uniq[$i][0] : $tr_uniq[$i];	        			
		        		$css = $valCss; 
		        		
		        		//td css : 1 - normal , 2 - uniq 
		            		$td_css_new = (is_array($tr_uniq[$i]) )? $tr_uniq[$i][1] : $td_css;
		            		$td_uniq_new = (isset($tr_uniq[$i][2]))? $tr_uniq[$i][2] : $td_uniq; 		
		        }
		        
		        //declare td css and unique css
            		$td_css_new = ($td_css_new != null)? $td_css_new : $td_css;
            		$td_uniq_new = ($td_uniq_new != null)? $td_uniq_new : $td_uniq;
            		
            		//write new cols
			$rows .= $this -> row( $key, $css, $td_css_new, $td_uniq_new );
			
			//clear $
			$td_uniq_new = null;
			$td_css_new = null;
			$css = '';
			
			$i++;
		}
		return $rows;
	}
	
}//table
