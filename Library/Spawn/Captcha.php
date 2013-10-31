<?php
/**
* Spawn Framework
*
* Class to captcha
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;

class Captcha 
{
	
	/**
	* captcha configuration
	*
	* @var array
	*/ 
	protected $_config;
	
	/**
	* @var Image instance
	*/
	protected $_img;
	
	protected $_session;
	
	/**
	* Use Session:load if $session = null
	*
	* @param Session $session null
	*/
	public function __construct(Session $session = null)
	{
	    $this -> _session = (null == $session)? Session::load() : $session;
	}
		
	/**
         * image background
         */
	protected function _setBackground()
	{		
			$this -> _img -> fill($this -> _config['background']);
	}
	
	/**
         * create gradient
         */
	protected function _setGradient()
	{
		$this -> _img -> gradient(				
			array( mt_rand(180,255), mt_rand(180,255), mt_rand(180,255) ),
			array( mt_rand(180,255), mt_rand(180,255), mt_rand(180,255) ),
			$this -> _config['width'],
			$this -> _config['height']
		);		
	}	
	
	/**
         * create random lines
         */
	protected function _setLines()
	{		
		$linesCount = mt_rand($this -> _config['lenght'], $this -> _config['lenght']+3);
		for($i=1; $i<=$linesCount; $i++){
			$this -> _img -> line(mt_rand(0, $this -> _config['width']), 0,
				 mt_rand(0, $this -> _config['width']), $this -> _config['height'],
				array(
					mt_rand(1,255), mt_rand(1,255), mt_rand(1,255)
				)
			);
		}
			
	}
	
	/**
         * create random text
         */
	protected function _setText()
	{	
		$this -> textArrayCount = count($this -> textArray) - 1;			
		$this -> _selfSize = $this -> _config['width'] / $this -> _config['lenght'] / 2;
		
		for($i=1; $i <= $this -> _config['lenght']; $i++){
			$this -> captchaStr .= $str = $this -> textArray[ mt_rand(0, $this -> textArrayCount) ];
			$this -> left += $this -> _selfSize;
			$this -> _writeText($str,30);				
		}		
	}	
	
	/**
	*write string in image
	*
	*@param string $str
	*@param integer $angle
	*@param integer $size
	*/
	protected function _writeText($str, $angle, $size = 0)
	{		
		$size = $this -> _size() + $size;
		$this -> _selfSize = $size;
		
		$top = $this -> _config['height'] / 2 + $size / 2;
		$font = $this -> _config['fonts'][ mt_rand(0, $this -> fontCount) ];
		
		$angle = mt_rand(-$angle, $angle);
		
		$this -> _img -> fttext($str, $size, $this -> left, $top,
			array(mt_rand(1, 120), mt_rand(1, 120), mt_rand(1, 120)),
			$this -> _config['fontPath'] . $font, $angle
		);
	}
	
	/**
         * create math (1+4 etc.)
         */
	protected function _setMath()
	{
		$int_1 = mt_rand($this -> _config['math_int1'][0], $this -> _config['math_int1'][1]);
		$int_2 = mt_rand($this -> _config['math_int2'][0], $this -> _config['math_int2'][1]);
		
		$math = mt_rand(0, count($this -> _config['math_math'])-1);
		$math = $this -> _config['math_math'][ $math ];
		
		switch($math){
			case '+':  $this -> captchaStr = $int_1 + $int_2; break;
			case '-':  $this -> captchaStr = $int_1 - $int_2; break;
			case '*':  $this -> captchaStr = $int_1 * $int_2; break;
			case '/':  $this -> captchaStr = $int_1 / $int_2; break;
		}
				
		//write int 1		
		$this -> _writeText($int_1, 10);
		
		//write math_math
		$this -> left += $this -> _selfSize * strlen($int_1);		
		$this -> _writeText($math, 5, 3);
		
		//write int_2
		$this -> left += $this -> _selfSize;			
		$this -> _writeText($int_2, 30);
		
	}//setMath
	
	/**
	*render captcha image and set code to session
	*
	*@param string $name captcha name to valid
	*/
	public function render($name = 'default')
	{
		$this -> _config = Config::load('Captcha') -> getAll();	
		$this -> _img = new Image($this -> _config['width'], $this -> _config['height']);
		$this -> captchaStr = null;	
		$this -> fontCount = count($this -> _config['fonts']) - 1;		
		$this -> left = mt_rand(0, 10);
		
		//array to random string
		$this -> textArray = array('A','B','C','D','E','F','G','H','J','K','M','N','P','R','T','W','Z','Y','X',2,3,4,6,7,8);
			
		//create image//
			
		if(is_array($this -> _config['background']) && count($this -> _config['background']) === 3) $this -> _setBackground();
		
		if($this -> _config['gradient'] == true) $this -> _setGradient();
		
		if($this -> _config['randomLinesBottom'] == true) $this -> _setLines();
				
		if($this -> _config['randomText'] == true) $this -> _setText();
		
		if($this -> _config['math'] == true) $this -> _setMath();			
		
		if($this -> _config['randomLinesTop'] == true) $this -> _setLines();
		
		
		//effects
		$effects = array('blur', 'embossing', 'edgedetect', 'meanRemoval');
		foreach($this -> _config['effects'] as $key){
			if(in_array($key, $effects)){
				$this -> _img -> $key();
			}else{			   
				throw new CaptchaException('Unknown captcha effect: '.$key);
			}
		}
								
		unset($this -> _config);
		$this -> _session -> set('sfCaptcha['.$name.']', $this -> captchaStr);	
			
		//create image
		header("Cache-Control: no-cache, must-revalidate");
		$this -> _img -> render();				
	}
	
	
	/**
	*check whether the captcha session isset
	*
	*@param string -$name captcha name
	*@return string
	*/
	public function isDeclared($name = 'default')
	{		
		return $this -> _session -> get('sfCaptcha['.$name.']', null);
	}
	
	
	/**
         *
         * @param string $pass
         * @param string $name
         * @return bool
         */
	public function isValid($pass, $name = 'default')
	{	
		return ($this -> _session -> get('sfCaptcha['.$name.']') == $pass )? true : false;
	}
	
	/**
         * create random size to captcha image
         */
	protected function _size()
	{
		return mt_rand(
				$this -> _config['width'] / ($this -> _config['lenght'] + 4 ) + 2,
				$this -> _config['width'] / ($this -> _config['lenght'] + 2 )
			);
	}
	
}//captcha

class CaptchaException extends \Exception{}
