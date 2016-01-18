<?php
class Bootstrap extends Yaf\Bootstrap_Abstract{

    private $appConfig;

    public function _init(Yaf\Dispatcher $dispatcher){
        \Yaf\Session::getInstance()->start();
        $this->appConfig = \yol\base\Helper::getConfig('application');
    }

    public function _initLoader(Yaf\Dispatcher $dispatcher){
        //注册本地类(先找项目library再找全局library)
        $loader = Yaf\Loader::getInstance();
        $loader->registerLocalNamespace(array('Local'));
    }

    public function _initService(Yaf\Dispatcher $dispatcher){
        $container = yol\di\Container::getInstance();
        $container->set('db', function(){
           return new yol\db\DbQuery( new yol\db\Connection($this->appConfig['db']) );
        });

    }

	public function _initPlugin(Yaf\Dispatcher $dispatcher) {

        //开启debug控制台输出
        if(APP_DEBUG){
           $dispatcher->registerPlugin(new yol\plugins\Debug());
        }

        //开启性能分析
        if( APP_ANALYZE && function_exists('xhprof_enable') ) {
            $dispatcher->registerPlugin(new yol\plugins\Xhprof());
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
