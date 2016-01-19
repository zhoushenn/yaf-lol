## yaf-lol

yaf框架增强， 添加一些基础组件，使yaf能更快速简单的应用在真实的项目开发中。

## 增强特新
1. [基于PDO的轻量级数据库访问组件，并提供读写分离。](#a1)
2. [简单的服务容器](#a2)
3. 框架基础业务封装组件（基类控制器，基类模型，辅助Helper类）
4. 提供一些插件(phpconsole调试插件,布局插件,xhprof性能分析插件)
5. 提供简单易用的验证组件，可以方便实现数据验证。
6. 整合phpunit、phpdocumentor、composer
7. 提供swoole异步队列组件
8. MVC增加一层Bll业务逻辑层

<a name="#a1"></a>
```php
    $query = new DbQuery( new Connection(...) );
    $query->from()->join()->where()->group()->order()->limit()->select();
```

<a name="#a2"></a>
在bootstrap中统一管理服务组件
```php
    public function _initService(Yaf\Dispatcher $dispatcher){
        $container = yol\di\Container::getInstance();
        $container->set('db', function(){
           return new yol\db\DbQuery( new yol\db\Connection($this->appConfig['db']) );
        });

    }
```
