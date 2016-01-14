<?php
class Bootstrap extends Yaf\Bootstrap_Abstract{

    private $config;
    private $app;

    public function _init(Yaf\Dispatcher $dispatcher){

        \Yaf\Session::getInstance()->start();
    }

    public function _initConfig(Yaf\Dispatcher $dispatcher) {
        $this->config = Yaf\Application::app()->getConfig()->toArray();
	}

    public function _initLoader(Yaf\Dispatcher $dispatcher){
        //注册本地类(先找项目library再找全局library)
        $loader = Yaf\Loader::getInstance();
        $loader->registerLocalNamespace(array('App'));
    }

    public function _initService(Yaf\Dispatcher $dispatcher)
    {
        $service = yol\base\ServiceLocator::getInstance();
    }

	public function _initPlugin(Yaf\Dispatcher $dispatcher) {

        //开启debug控制台输出
        if(APP_DEBUG){
           //$dispatcher->registerPlugin(new DebugPlugin());
        }

        //开启性能分析
        if( APP_ANALYZE && function_exists('xhprof_enable') ) {
            $dispatcher->registerPlugin(new XhprofPlugin());
        }
	}

	public function _initRoute(Yaf\Dispatcher $dispatcher) {
        /*
        //http://www.laruence.com/manual/yaf.routes.static.html#yaf.routes.simple
        //路由注册的顺序很重要, 最后注册的路由协议, 最先尝试路由, 这就有个陷阱. 请注意.
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

}
