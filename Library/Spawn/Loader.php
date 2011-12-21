<?php
/**
* Spawn Framework
*
* Loader
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
namespace Spawn;
class Loader
{
    /**
     * @var array
     */
    protected $_classPath = array();

    /**
     * @var array
     */
    protected $_includePath = array();

    /**
     * @param string $name
     * @param string $path
     */
    public function setClassPath($name, $path)
    {
        $this->_classPath[ $name ] = $path;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getClassPath()
    {
        return $this->_classPath;
    }

    /**
     *
     * @param string $path
     * @return Loader
     */
    public function setIncludePath($path)
    {
        $this->_includePath[] = $path;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getIncludePath()
    {
        return $this->_includePath;
    }

    /**
     *
     * @return Loader
     */
    public function register()
    {
        spl_autoload_register(array($this, 'load'));
        return $this;
    }

    /**
     *
     * @return Loader
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'load'));
        return $this;
    }

    /**
     * @param string $className
     * @return $this
     */
    public function load($className)
    {
        if( !isset( $this->_classPath[ $className ] ) ){
            $className = ltrim($className, '\\');
            $fileName  = '';
            $namespace = '';
            $lastNsPos = strripos($className, '\\');
            if ( null != $lastNsPos) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';  
        }else{
            $fileName = $this->_classPath[ $className ];
        }
        
        foreach($this->_includePath as $key){
            if( file_exists(ROOT_PATH . $key . $fileName) ){
		        require_once(ROOT_PATH . $key . $fileName);
		        return;
            }
        }
        return $this;
    }
    
}//Loader
