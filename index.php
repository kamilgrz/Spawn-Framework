<?php         
/**
* index page
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
*/
error_reporting(E_ALL|E_STRICT);

date_default_timezone_set('Europe/Warsaw');

define('SPAWNM','2.1.8');
define('ROOT_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

include_once(ROOT_PATH . 'Library/Spawn/Loader.php');
include_once(ROOT_PATH . 'Library/Spawn/Router.php');
include_once(ROOT_PATH . 'Library/Spawn/Spawn.php');

set_error_handler( array('\Spawn\Spawn', 'exception') );
set_exception_handler( array('\Spawn\Spawn', 'exception') );

$loader = new \Spawn\Loader;
$loader -> setIncludePath('Library/')
        -> setIncludePath('Application/')
        -> register();

$spawn = new \Spawn\Spawn;
$spawn -> router = new \Spawn\Router;
$spawn -> loader = $loader;
$spawn -> controller = 'Spawn';
$spawn -> action = 'index';
$spawn -> init();
?>
