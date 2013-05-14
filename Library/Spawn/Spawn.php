<?php
/**
 * Spawn Framework
 *
 * Front controller
 *
 * @author  Paweł Makowski
 * @copyright (c) 2010-2013 Paweł Makowski
 * @license http://spawnframework.com/license New BSD License
 */
namespace Spawn;
final class Spawn
{
    /**
     * @var \Spawn\Loader
     */
    public $loader;

    /**
     * @var \Spawn\Router
     */
    public $router;

    /**
     * default action
     * @var string
     */
    public $action;

    /**
     * default controller
     * @var string
     */
    public $controller;

    /*
    * @var string
    */
    public static $controllerSeparator = '-';

    /**
     * load bootstrap.php
     */
    public function bootstrap($di)
    {
        $bootstrap = new \Bootstrap($di);
        $bootstrap->start();
    }

    /**
     * load page
     */
    public function init()
    {
        ob_start();
        try{
        	$this->baseDetect();
            $di = new DI;
            $firewall = new \Spawn\Firewall($di);
            $this -> event  = new Event($di);
            $di->set('event', $this->event);
            $di->set('firewall', $firewall);
            $this -> bootstrap($di);

            $this -> event  -> run('Spawn.Ready') -> delete('Spawn.Ready');

            register_shutdown_function( array($this, 'shutdown') );

            $uri = new Request\Uri();
            $uri -> initPath() -> initArgs();
            $controller_core = $uri->param(0, $this -> controller);
            $controller = '\Controller\\' .str_replace(' ', '\\',ucwords(str_replace(self::$controllerSeparator, ' ', $controller_core)));
            $action     = $uri->param(1, $this -> action ).'Action';

            if( !class_exists($controller) or (class_exists($controller) && !method_exists($controller, $action )) ){
                $controller_core = $controller_core . self::$controllerSeparator . $this->controller;
                $controller = $controller.'\\'.$this->controller;
            }

            if( !class_exists($controller) or (class_exists($controller) && !method_exists($controller, $action )) ){
                $this -> router -> init( $uri );
                $uri = $this -> router -> getUri();
                $controller = '\Controller\\' . str_replace(' ', '\\', ucwords(str_replace(self::$controllerSeparator, ' ',  $this -> router -> getController())));
                $action = $this -> router -> getAction() . 'Action';
            }else{
                $uri -> setParam(0, $controller_core ) -> setParam(1, $uri->param(1, $this -> action ) );
            }

            if( !class_exists($controller) or (class_exists($controller) && !method_exists($controller, $action )) ){
                self::error404();
            }
            $firewall->start();
            $di->delete('firewall');
            $this -> event  -> run('Spawn.Execute') -> delete('Spawn.Execute');

            $controller =  new $controller;
            $request = new Request();
            $request -> uri = $uri;
            $di->set('request', $request);
            $controller->di = $di;
            $controller -> request = $request;
            $controller -> loader = $this -> loader;
            $controller -> event = $this -> event;
            $controller -> init();
            $controller -> $action( $request );
            $controller -> end();
            echo $controller -> response;
            $this -> event  -> run('Spawn.Finish') -> delete('Spawn.Finish');

        }catch(\Exception $error) {
            $this -> event  -> run('Spawn.Exception') -> delete('Spawn.Exception');
            $buff = ob_get_contents();
            ob_end_clean();
            include_once(ROOT_PATH . 'Application'. DIRECTORY_SEPARATOR .'View'. DIRECTORY_SEPARATOR .'Error'. DIRECTORY_SEPARATOR .'exception.phtml');
        }
    }
	
	/**
	*
	*/
    public function shutdown()
    {
        $this -> event -> run('Spawn.Shutdown');
    }
    
    /**
    *
    */
    public function baseDetect()
    {
        $sn = explode('/', $_SERVER['SCRIPT_NAME']);
        $index = current(array_reverse($sn));
        $base = str_replace($index,'',$_SERVER['SCRIPT_NAME']);

        if(Config::load('Uri')->get('base') != $base) {
            $config = include(ROOT_PATH.'Bin/Config/Uri.php');

            $config['base'] = $base;
            Config::load('Uri')->set('base',$base);

            $data = '<?php '.PHP_EOL.'return $config = '.var_export($config, true).';';
            file_put_contents(ROOT_PATH.'Bin/Config/Uri.php', $data);
        }
    }


    /**
     *render 404 page
     */
    public static function error404()
    {
        $buff = ob_get_contents();
        ob_end_clean();
        $di = new DI;
        $di->event-> run('Spawn.404') -> delete('Spawn.404');
        $error = new \Controller\Error();
        $error -> indexAction();
        echo $error -> response;
        exit;
    }

    /**
     *
     */
    public static function exception( $severity, $message, $filename, $lineno )
    {
        if (0 == error_reporting()) {
            return;
        }
        if( class_exists('Log') ){
            Log::add($message.' File: '.$filename.' Line: '.$lineno);
        }
        throw new \ErrorException($message, 0, $severity, $filename, $lineno);
    }



}//spawn
