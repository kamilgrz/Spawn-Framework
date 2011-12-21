<?php
/**
* Spawn Framework
*
* Gravatar
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Helper
*/
namespace Spawn\View\Helper;
class Gravatar
{
    /**
     * The email address
     * @var string
     */
    public $email;

    /**
     * Size in pixels, defaults to 80px [ 1 - 512 ]
     * @var integer
     */
    public $s = 80;

    /**
     * Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @var string
     */
    public $d = 'mm';

    /**
     * Maximum rating (inclusive) [ g | pg | r | x ]
     * @var sring
     */
    public $r = 'g';

    /**
     * declare email
     * @param string $email
     */
    public function  __construct($email=null)
    {
        $this -> email = $email;
    }
    
    /**
    * @param string $mail
    * @return $this
    */
    public function setMail($mail)
    {
        $this -> email = $mail;
        return $this;
    }

    /**
     * return url to img tag
     * @return string
     */
    public function getUrl()
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $this -> email ) ) );
        $url .= '?s='.$this -> s.'&d='.$this -> d.'&r='.$this -> r;
        return $url;
    }

    /**
     * return html img tag
     * @param array $atts
     * @return string
     */
    public function getImage(array $atts = array())
    {
        $url = '<img src="' . $this -> getUrl() . '"';
        foreach ( $atts as $key => $val ){
            $url .= ' ' . $key . '="' . $val . '"';
        }
        $url .= '>';
        return $url;
    }

}
