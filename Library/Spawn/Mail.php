<?php
/**
* Spawn Framework
*
* Mail
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Mail 
{
    /**
     *
     * @var string
     */
	protected $_formTitle;

        /**
         *
         * @var string
         */
	protected $_fromMail;
	
	/**
	*declare header 'from:' title 
	*
	* @param string $val
	* @return $this
	*/
	public function setFromTitle($val)
	{
		$this -> _fromTitle = $val;
		return $this;
	}
	
	/**
	*declare header 'from:' mail 
	*
	* @param string $val
	* @return $this
	*/
	public function setFromMail($val)
	{
		$this -> _fromMail = $val;
		return $this;
	}

	/**
	*send html mail 
	*
	*@param string $to
	*@param string $subject
	*@param string $message
	*@return bool
	*/
	public function send($to, $subject, $message)
	{
		$headers  = 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-Type: text/html; charset=utf-8'."\n".
		$headers .= 'From: ' . $this -> _fromTitle . ' <' . $this -> _fromMail . '>' . "\n";
		$headers .= 'Reply-To: '.$this -> _fromMail."\n";
		return mail( $to , $subject , $message , $headers );
	}
	
}//mail
