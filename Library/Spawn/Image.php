<?php
/**
* Spawn Framework
*
* GD
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;

class Image 
{

	/**
         * @var image handler
         */
	protected $_img = null;

	/**
         * @var string
         */
	protected $_imgInfo = null;

	/**
         *
         * @var GD color
         */
	protected $_color = null;
	
	/**
	*create image handler
	*
	*@param string|integer $image file name OR width
	*@param integer $height
	*/
	public function __construct($image, $height = null)
	{
		if(null == $height){
			$finfo = new \finfo(FILEINFO_MIME_TYPE);
            $this -> _mime = $finfo -> file($image);
			if($this -> _mime == 'image/gif'){ 
				$this -> _img = imagecreatefromgif($image);
			}elseif($this -> _mime == 'image/jpeg' or $this -> _mime == 'image/jpg'){ 
				$this -> _img = imagecreatefromjpeg($image);
			}elseif($this -> _mime == 'image/png'){ 
				$this -> _img = imagecreatefrompng($image);
			}else{
				throw new ImageException('This is not a image (jpg | png | gif)!');
			}
		}else{
			$this -> _img = imagecreatetruecolor($image, $height);		
		}		
	}
	
	/**
	*get image width
	*
	*@return integer
	*/
	public function width()
	{
		return imagesx($this -> _img);
	}
	
	/**
	*get image height
	*
	*@return integer
	*/
	public function height()
	{
		return imagesy($this -> _img);
	}
	
	
	/**
         * @return image handler
         */
	public function getImage()
	{
		return $this -> _img;
	}
		
	/**
	*draw line
	*
	*@param integer $x1
	*@param integer $y1
	*@param integer $x2
	*@param integer $y2
	*@param array $color
	*@return $this
	*/
	public function line($x1, $y1, $x2, $y2, array $color = array() )
	{
		$color = $this -> _getColor($color);
		imageline($this -> _img, $x1, $y1, $x2, $y2, $color);
		return $this;
	}
	
	/**
	*declare color
	*
	*@param integer $r
	*@param integer $g
	*@param integer $b
	*@return $this
	*/
	public function color( $r,  $g, $b)
	{
		$this -> _color = imagecolorallocate($this -> _img, $r, $g, $b);
		return $this;
	}
	
	/**
	*declare and return color
	*
	*@param array $color
	*@return imagecolorallocate() :)
	*/
	protected function _getColor(array $rgb = array() )
	{
		if(count($rgb) == 3){
			$this -> color($rgb[0], $rgb[1], $rgb[2]);
		}
		return $this -> _color;
	}
	
	/**
	*declare image background
	*
	*@param array $color
	*@param integer $x - start position
	*@param integer $y - start position
	*@return $this
	*/
	public function fill( array $rgb = array(), $x = 0, $y = 0)
	{		
		$color = $this -> _getColor($rgb);
		imagefill($this -> _img, $x, $y, $color);
		return $this;
	}
	
	/**
	*use emboss filter
	*
	*@return $this
	*/
	public function embossing()
	{		
		imagefilter($this -> _img, IMG_FILTER_EMBOSS);
		return $this;
	}
	
	/**
	*use blur filter
	*
	*@return $this
	*/
	public function blur()
	{
		imagefilter($this -> _img, IMG_FILTER_GAUSSIAN_BLUR);
		return $this;
	}
	
	/**
	*use negate filter
	*
	*@return $this
	*/
	public function negate()
	{
		imagefilter($this -> _img, IMG_FILTER_NEGATE);
		return $this;
	}
	
	/**
	*use brightness filter
	*
	*@param int $arg
	*@return $this
	*/
	public function brightness($arg)
	{
		imagefilter($this -> _img, IMG_FILTER_BRIGHTNESS, $arg);
		return $this;
	}
	
	/**
	*use contrast filter
	*
	*@param int $arg
	*@return $this
	*/
	public function contrast($arg)
	{
		imagefilter($this -> _img, IMG_FILTER_CONTRAST, $arg);
		return $this;
	}
	
	
	/**
	*use colorize filter
	*
	*@param int $r
	*@param int $g
	*@param int $b
	*@param int $alpha
	*@return $this
	*/
	public function colorize($r, $g, $b, $alpha)
	{
		imagefilter($this -> _img, IMG_FILTER_COLORIZE, $r, $g, $b, $alpha);
		return $this;
	}
	
	/**
	*use edgedetect filter
	*
	*@return $this
	*/
	public function edgedetect()
	{
		imagefilter($this -> _img, IMG_FILTER_EDGEDETECT);
		return $this;
	}
	
	/**
	*use mean removal filter
	*
	*@return $this
	*/
	public function meanRemoval()
	{
		imagefilter($this -> _img, IMG_FILTER_MEAN_REMOVAL);
		return $this;
	}
	
	/**
	*use smooth filter
	*
	*@param int $arg
	*@return $this
	*/
	public function smooth($arg)
	{
		imagefilter($this -> _img, IMG_FILTER_SMOOTH, $arg);
		return $this;
	}
	
			
	/**
	*get gradient to image
	*
	*@param array $color1
	*@param array $color2
	*@param integer $imageWidth - if null - imagesx
	*@param integer $imageHeight - if null - imagesy
	*@return $this
	*/
	public function gradient(array $color1, array $color2,  $imageWidth=null, $imageHeight=null)
	{
		$imageWidth = (null != $imageWidth)? $imageWidth : imagesx($this -> _img);
		$imageHeight = (null != $imageHeight)? $imageHeight : imagesy($this -> _img);
		
		// make the gradient
		for($i = 0; $i < $imageHeight; $i++)
		{
			$colorR = floor($i * ($color2[0] - $color1[0]) / $imageHeight) + $color1[0];
			$colorG = floor($i * ($color2[1] - $color1[1]) / $imageHeight) + $color1[1];
			$colorB = floor($i * ($color2[2] - $color1[2]) / $imageHeight) + $color1[2];

			$color = ImageColorAllocate($this -> _img, $colorR, $colorG, $colorB);
			imageline($this -> _img, 0, $i, $imageWidth, $i, $color);
		}

		return $this;
	}
	
	/**
	*rotete image
	*
	*@param float $angle
	*@param array $color
	*@return $this
	*/
	public function rotate($angle, $color = array() )
	{		
		$color = $this -> _getColor($color);
		$this -> _img = imagerotate($this -> _img, $angle, $color);
		return $this;
	}
		
	
	/**
	*write text | see imagefttext in php.net :)
	*
	*@param string $text
	*@param integer $size
	*@param integer $x
	*@param integer $y
	*@param array $color
	*@param string $fontFile
	*@param float $angle
	*@return $this
	*/	
	public function fttext($text, $size, $x = 0, $y = 0, array $color = array(), $fontFile, $angle = 0 )
	{
		$color = $this -> _getColor($color);
		imagefttext($this -> _img, $size, $angle, $x, $y, $color,$fontFile, $text);
		return $this;
	}
	
	/**
	*resize image
	*
	*@param integer $width
	*@param integer $height
	*@return $this
	*/
	public function resize($width, $height)
	{
		$im2 = imagecreatetruecolor($width, $height);
		imagecopyresampled($im2, $this -> _img, 0, 0, 0, 0, $width, $height, imagesx($this -> _img), imagesy($this -> _img));
		$this -> _img = $im2;
		
		return $this;
	}
	
	/**
	*resize image with proportions
	*
	*@param integer $maxWidth
	*@param integer $maxHeight
	*@param string $size (max | min)
	*@return $this
	*/
	public function trueResize($maxWidth, $maxHeight, $size = 'max')
	{
		$startW = imagesx($this -> _img);
		$startH = imagesy($this -> _img);
					
		$heightRatio = $startH / $maxHeight;
		$withRatio = $startW / $maxWidth;

		$scale = $size($withRatio, $heightRatio);

		$newHeight = floor($startH / $scale);
		$newWidth = floor($startW / $scale);
				
		//create new image
		$im2 = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($im2, $this -> _img, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($this -> _img), imagesy($this -> _img));
		
		$this -> _img = $im2;
						
		return $this;
	}
	
	/**
	*use grayScale efect
	*
	*@return $this
	*/
	public function grayScale()
	{
		 imagefilter($this -> _img, IMG_FILTER_GRAYSCALE);
		 return $this;
	}
	
	/**
	*write text
	*
	*@param string $text
	*@param integer $font
	*@param integer $x
	*@param integer $y
	*@param array $color
	*@return $this
	*/	
	public function text($text, $font = 6, $x = 5, $y = 5, array $color = array() )
	{		
		$color = self::_getColor($color);		
		imagestring($this -> _img, $font, $x, $y, $text, $color);
		return $this;
	}	
	
	/**
	*save image to image file
	*
	*@param string $save file name
	*/		
	public function save($save)
	{	 	
		if($this -> _mime == 'image/gif' ){ 
			imagegif($this -> _img, $save);
		}elseif($this -> _mime == 'image/jpeg' or $this -> _mime == 'image/jpg'){ 
			imagejpeg($this -> _img, $save);
		}elseif($this -> _mime == 'image/png'){ 
			imagepng($this -> _img, $save);
		}				
	}
	
	
	/**
	*save image to image file
	*
	*@param string $save file name
	*@param string $type mimeType
	*/
	public function saveAs($save, $type = 'image/png')
	{
		$type=trim(strtolower($type));
		if($type == 'image/gif'){
			imagegif($this -> _img, $save);
		}elseif($type == 'image/jpeg' or $type == 'image/jpg'){
			imagejpeg($this -> _img, $save);
		}elseif($type == 'image/png'){
			imagepng($this -> _img, $save);
		}
	}
	
	/**
         * render image
         */
	public function render()
	{		
		if($this -> _imgInfo != null){
			if($this -> _mime == 'image/gif'){
				header("Content-type: image/gif");
				imagegif($this -> _img);
			}elseif($this -> _mime == 'image/jpeg' or $this -> _mime == 'image/jpg'){
				header("Content-type: image/jpeg");
				imagejpeg($this -> _img);
			}elseif($this -> _mime == 'image/png'){
				header("Content-type: image/png");
				imagepng($this -> _img);
			}			
		}else{
			header('Content-type: image/png');
			imagepng($this -> _img);
		}
	}
	
	/**
	* imagedestroy :)
	*/
	public function __destruct()
	{
		imagedestroy($this -> _img);
	}
	
}//image

class ImageException extends \Exception {}
