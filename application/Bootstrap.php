<?php
class Bootstrap extends Yaf\Bootstrap_Abstract{

    private $config;
    private $app;

    public function _init(Yaf\Dispatcher $dispatcher){
        define('APP_PATH', APPLICATION_PATH . '/application');
        define('DATA_PATH', APP_PATH . '/data');
        define('APP_DEBUG', true);
        define('APP_ANALYZE', true); //性能分析
        $this->app = Yaf\Application::app();
    }

    public function _initConfig() {
        $this->config = $this->app->getConfig()->toArray();
	}

    public function _initService(Yaf\Dispatcher $dispatcher){
        $service = new Extend\zsdi\ServiceLocator();
        $service->set('db', function(){
            $dbconfig = $this->config['db'];
            return new Extend\zsdbal\Connection($dbconfig);
        });
        $service->set('log', function(){
            $logConfig = $this->config['log'];
            $log = new Monolog\Logger($logConfig['loger']);
            $log->pushHandler(
                new Monolog\Handler\StreamHandler($logConfig['path'].'/'.date('Y-m-d').'.log',
                    Monolog\Logger::ERROR)
            );
            return $log;
        });
        $this->app->service = $service;
    }

	public function _initPlugin(Yaf\Dispatcher $dispatcher) {

        $dispatcher->registerPlugin(new LayoutPlugin());

		if(APP_DEBUG){
            //调试信息输出
			$dispatcher->registerPlugin(new DebugPlugin());
		}
        //性能信息输出
        if( APP_ANALYZE && function_exists('xhprof_enable') ) {
            $dispatcher->registerPlugin(new XhprofPlugin());
        }
	}

	public function _initRoute(Yaf\Dispatcher $dispatcher) {
        /*
        //http://www.laruence.com/manual/yaf.routes.static.html#yaf.routes.simple
        $route = new Yaf\Route\Rewrite('/', [
                'module' => 'index',
                'controller' => 'index',
                'action' => 'index',
            ]);

        $dispatcher->getRouter()->addRoute('test', $route);
        */
	}

	public function _initView(Yaf\Dispatcher $dispatcher){
	}

    public function _initErrorHandle(){
        if(APP_DEBUG){
            return ;
        }
        error_reporting(E_ERROR | E_PARSE);
        ini_set('display_errors', 0);
        $loger = $this->app->service->log;
        set_error_handler(function($errno, $errstr, $errfile, $errline) use ($loger){
            $loger->addError('ERROR_HANDLER:'.$errstr);
        });
        register_shutdown_function(function() use ($loger){
            $error = error_get_last();
            if($error){
                $loger->addError('SHUTDOWN_HANDLER:'.var_export($error, true));
            }
        });
    }
}
