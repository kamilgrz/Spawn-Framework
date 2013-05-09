<?php         
/**
* index page
*
* @author  Paweł Makowski
* @copyright (c) 2010-2013 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
error_reporting(E_ALL|E_STRICT);

date_default_timezone_set('Europe/Warsaw');

define('SPAWNM','2.5.1');
define('ROOT_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

include_once(ROOT_PATH . 'Library'.DIRECTORY_SEPARATOR.'Spawn'.DIRECTORY_SEPARATOR.'Loader.php');
include_once(ROOT_PATH . 'Library'.DIRECTORY_SEPARATOR.'Spawn'.DIRECTORY_SEPARATOR.'Router.php');
include_once(ROOT_PATH . 'Library'.DIRECTORY_SEPARATOR.'Spawn'.DIRECTORY_SEPARATOR.'Spawn.php');
include_once(ROOT_PATH . 'Library'.DIRECTORY_SEPARATOR.'Spawn'.DIRECTORY_SEPARATOR.'BootstrapAbstract.php');
include_once(ROOT_PATH . 'Library'.DIRECTORY_SEPARATOR.'Spawn'.DIRECTORY_SEPARATOR.'DI.php');
include_once(ROOT_PATH . 'Application'.DIRECTORY_SEPARATOR.'Bootstrap.php');

set_error_handler( array('\Spawn\Spawn', 'exception') );
set_exception_handler( array('\Spawn\Spawn', 'exception') );

$loader = new \Spawn\Loader;
$loader -> setIncludePath('Library'.DIRECTORY_SEPARATOR)
        -> setIncludePath('Application'.DIRECTORY_SEPARATOR)
        -> setIncludePath('Application'.DIRECTORY_SEPARATOR.'Helper'.DIRECTORY_SEPARATOR)
        -> register();

$spawn = new \Spawn\Spawn;
$spawn -> router = new \Spawn\Router;
$spawn -> loader = $loader;
$spawn -> controller = 'Spawn';
$spawn -> action = 'index';
$spawn -> init();
?>

