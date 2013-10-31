<?php
/**
* Spawn Framework
*
* RSS generator
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn\Feed;
class Rss
{	
    /**
    * @var \DOMDocument
    */
    public $dom;    
    
	/**
         *
         * @param float $version
         */
	public function __construct($version = 2.0)
	{
		$this -> dom = new \DOMDocument();
		
		//root elelement 
		$rss = $this -> dom -> createElement('rss');

		$rss -> setAttribute('version', $version);

		//append the attribute to the xml
		$this -> dom -> appendChild($rss);


		//create channel element.
		$this -> channel = $this -> dom -> createElement('channel');

		$rss -> appendChild($this -> channel);
	}
	
	
	/**
	*Create the main child nodes of channel
	*
	* @param string $name
	* @param string $val
        * @return $this
	*/	
	public function main($name, $val)
	{
		$name = $this -> dom -> createElement($name, $val);
		$this -> channel -> appendChild( $name );
        return $this;
	}

	
	/**
	* Create rss image
	*
	* @param string url
	* @param string title
        * @return $this
	*/
	public function image($url, $title)
	{
		$url   = $this -> dom -> createElement('url', $url);
		$title = $this -> dom -> createElement('title', $title);
	
		$image = $this -> dom -> createElement('image');
		$this -> channel -> appendChild($image);
		
		$image -> appendChild($url);
		$image -> appendChild($title);
        return $this;
	}

	/**
	*Create rss item tree
	*
	* @param array $items
        * @return $this
	*/
	public function threads(array $items)
	{
		foreach ($items as $thread){
			$item  = $this -> dom -> createElement('item');
			
			//create item
			foreach($thread as $key => $val){
			
				//if is array (category?)
				if(is_array($val) or is_object($val)){
					foreach ($val as $val){
						$ce = $this -> dom -> createElement($key, $val);
						$item -> appendChild($ce);
					}
				}else{	
					$ce = $this -> dom -> createElement($key, $val);
					$item -> appendChild($ce);
				}
			}
			$this -> channel -> appendChild($item);		
		}
        return $this;
	}
	
	/**
         * @return string
         */
	public function create()
	{
		return  $this -> dom -> saveXML();
	}	
}//rss
