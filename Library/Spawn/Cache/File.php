<?php
/**
* Spawn Framework
*
* Class to cache data
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Cache
*/
namespace Spawn\Cache;
class File implements CacheInterface
{
	/**
	* @var string
	*/
	public $path = 'Bin/Cache/';

        /**
         *
         * @var integer
         */
        public $expire = 31536000;

	

	/**
	* find file
	*
	* @param string $name file name
	* @return bool
	*/
	public function exists($name)
	{
		return ( file_exists(ROOT_PATH . $this -> path . $name) 
		    && is_file(ROOT_PATH . $this -> path . $name) 
		    && true === $this -> _fileLife(ROOT_PATH . $this -> path . $name))? true : false;
	}

	/**
	* set data to cache
	*
	* @param string $name
	* @param mixed $data
	* @return $this
	*/
	public function set($name, $data)
	{
		$exp = time() + $this -> expire;
		$data = serialize($data);

		file_put_contents(ROOT_PATH . $this -> path . $name, $data);
		touch($this -> path . $name, $exp);

		return $this;
	}

	/**
	* return cached data
	*
	* @param string $name
	* @param mixed $or
	* @return mixed
	*/
	public function get($name)
	{
        $data = false;
		if(file_exists(ROOT_PATH . $this -> path . $name) 
		    && is_file(ROOT_PATH . $this -> path . $name) 
		    && true === $this -> _fileLife(ROOT_PATH . $this -> path . $name) )
		{
			$data = unserialize( file_get_contents(ROOT_PATH . $this -> path . $name) );
		}

		return $data;
	}

	/**
	* delete cache file
	*
	* @param string $name
	* @return $this
	*/
	public function delete($name)
	{
		return unlink(ROOT_PATH . $this -> path . $name);
	}

	/**
	* delete file if hes life is end :)
	*
	* @param string $file
	* @return bool
	*/
	protected function _fileLife($file)
	{
		if( filemtime($file) < time() ){
			unlink($file);
			return false;
		}
		return true;
	}

	/**
	* delete all cache files
	*
	* @return integer
	*/
	public function deleteAll()
	{
		$file = glob(ROOT_PATH . $this -> path . '*');
		$i = 0;
		if( !is_array($file) ){
			return $i;
		}

		foreach($file as $key){
			if(is_file($key)){
				unlink($key);
				$i++;
			}
		}
		return $i;
	}


	/**
	* deleted death files
	*
	* @param string $filesRegex
	* @return integer
	*/
	public function clean($filesRegex = '*')
	{
		$files = glob(ROOT_PATH . $this -> path . $filesRegex);
		$deleted = 0;

		if( !is_array($files) ){
			return $deleted;
		}

		foreach($files as $key){
			if( is_file($key) ){
				$deleted += (int) $this -> _fileLife($key);
			}
		}
		return $deleted;
	}

}//cache
