<?php
/**
* Spawn Framework
*
* Upload
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;

class Upload
{
	
	/**
    * @var array
    */
	protected $_file;

    /**
    * @var string
    */
	public $path = 'Media/Upload/';

    /**
    * @var string
    */
	protected $_newName;
	
	/**
    * @var Image
    */
	public $img = null;

    /**
    * @var array
    */
	protected $_acceptedMime = array();
	
	/**
	* max upload file in bytes
	* @var integer
	*/
	protected $_max = 1048576;

    /**
    *
    * @var integer
    */
	protected $_chmod = 0600;

    /**
    * @var string
    */
	protected $_error = null;

    /**
    * @var array
    */
	protected $_errorMessage = array(
		'Default'=>'Unknown upload error',
		'MaxFileSize' => 'Invalid file size',
		'AcceptedMime' => 'Invalid mime type',
		1=>'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
		2=>'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form. ',
		3=>'The uploaded file was only partially uploaded. ',
		4=>'No file was uploaded',
		6=>'Missing a temporary folder'
		);
	
	/**
    * configuration with /config/upload.php
    * @var array
    */
	protected $_config = array();
	
	
	/**
    *
    * @param array $file
    * @param string $newName
    */
	public function __construct( $file, $newName = null )
	{		
		$this -> _file = $file;
		$this -> _newName = $newName;
	}
    
    /**
    *
    * @param int $chmod
    * @return Upload
    */
    public function setChmod($chmod)
    {
        $this -> _chmod = $chmod;
        return $this;
    }
    
    /**
    *
    * @param array $arr
    * @return Upload
    */
    public function setErrorMessage(array $arr)
    {
        $this -> _errorMessage = $arr;
        return $this;
    }

    /**
    *
    * @param array $file
    * @param string $newName
    * @return Upload
    */
	public function setFile($file, $newName = null)
	{
	    $this -> _file = $file;
		$this -> _newName = $newName;
		return $this;
	}
	
	/**
	*use sf_image method if sf_upload havent 
	*
	*@param string $method
	*@param array $args
	*@return mixed
	*/
	public function __call($method, array $args)
	{
		//is sf_image ?
		if(!$this -> img instanceof \Spawn\Image) throw new UploadException('Declare image upload , Use toImage() method !');
			
		//method isset in sf_image?
		if( !method_exists($this -> img, $method) ) throw new UploadException('Method "' . $method . '" not found!');
			
		//use method
		$return = call_user_func_array(array($this -> img, $method), $args);
		return ($return instanceof \Spawn\Image) ? $this : $return;		
	}

    /**
    *
    * @return bool
    */
	public function isValid()
	{
	    try{
	        $this -> _valid();  
	        return true;  
	    }catch(UploadException $e){
	        $this -> _error = $e -> getMessage();
	        return false;
	    }
	}

        /**
         *
         * @return string
         */
	public function getError()
	{
	    return $this -> _error;
	}

    /**
    *
    */    
	protected function _valid()
	{
	    if( !$this -> _validFileError() ){
	        if( isset($this -> _errorMessage[ $this -> _file['error'] ]) ){
	            throw new UploadException( $this -> _errorMessage[ $this -> _file['error'] ] );
	        }
	        throw new UploadException( $this -> _errorMessage['Default'] );    
	    }
	    if( !$this -> _validMaxSize() ){
	        throw new UploadException( $this -> _errorMessage['MaxFileSize'] );    
	    }
	    if( !$this -> _validMime() ){
	        throw new UploadException( $this -> _errorMessage['AcceptedMime'] );
	    }
	}

        /**
         *
         * @return bool
         */
	protected function _validFileError()
	{
		if( $this -> _file['error'] !== \UPLOAD_ERR_OK ){
			return false;
		}
		return true;
	}
	
	/**
         *
         * @param integer $max
         * @return Upload
         */
	public function setMaxSize($max=null)
	{
		$this -> _max = $max;
		return $this;
	}

        /**
         *
         * @return bool
         */
	protected function _validMaxSize()
	{	
		if($this -> _file['size'] > $this -> _max or filesize($this -> _file['tmp_name']) > $this -> _max){
		    return false;			
		}    
		return true;
	}

        /**
         *
         * @param array $mime
         * @return Upload
         */
	public function setAcceptedMime(array $mime)
	{
	    $this -> _acceptedMime = $mime;
	    return $this;
	}

        /**
         *
         * @return bool
         */
	protected function _ValidMime()
	{
	    if( count($this -> _acceptedMime) < 1 ) return true;
	    $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $fmime = $finfo -> _file($this -> _file['tmp_name']);
            return ( in_array($fmime, $this -> _acceptedMime) )? true : false;
	}
	
	/**
	*upload file to server
	*
	*@return string|bool
	*/
	public function upload()
	{
		//create file name
		$fileName = $this -> _file['name'];
		$ext = '.' . pathinfo($fileName, PATHINFO_EXTENSION);
		
		//define new file name
		$newFileName = (null == $this -> _newName)? time() . mt_rand(5, 50) . $ext : $this -> _newName . $ext;			
		
		//save file
		if( is_uploaded_file( $this -> _file['tmp_name'] ) ){
			if( move_uploaded_file( $this -> _file['tmp_name'], ROOTpath . $this -> path . $newFileName ) ){
			   	chmod(ROOTpath . $this -> path . $newFileName, $this -> _chmod );
		      		return $newFileName;
		   	}
		}      
		return false;
	}
	
	
	/**
	*create sf_image instance with $_FILES
	*
	*@return bool 
	*/
	public function toImage()
	{
		$this -> img = new Image($this -> _file['tmp_name']);
	}
	
	/**
	*upload image to server
	*
	*@return string
	*/
	public function uploadImage()
	{
		//create file name
		$fileName = $this -> _file['name'];
		$ext = '.' . pathinfo($fileName, PATHINFO_EXTENSION);
		$newFileName = (null == $this -> _newName)? time() . mt_rand(5, 50) . $ext : $this -> _newName . $ext;		
		
		//upload image 		
		$this -> img -> save($this -> path . $newFileName);
		
		//return new file name
		return $newFileName;
	}
	
}//upload

class UploadException extends \Exception {}
