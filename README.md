## yaf-lol
yaf框架增强， 添加一些基础组件，使yaf能更快速简单地应用到真实的项目开发中。

## 增强特新
1. 基于PDO的轻量级数据库访问组件，并提供读写分离
    
    ```php
        $query = new DbQuery( new Connection(...) );
        $query->from()->join()->where()->group()->order()->limit()->select();
    ```
2. 简单的服务容器
    
    在bootstrap中统一管理服务组件
    ```php
        public function _initService(Yaf\Dispatcher $dispatcher){
            $container = yol\di\Container::getInstance();
            $container->set('db', function(){
               return new yol\db\DbQuery( new yol\db\Connection($this->appConfig['db']) );
            });

        }
    ```
3. 框架基础业务封装组件（基类控制器，基类模型，辅助Helper类）
4. 提供一些插件(phpconsole调试插件,布局插件,xhprof性能分析插件)
5. 提供简单易用的验证组件，可以方便实现数据验证。

    model层的数据验证
    ```php
    class UserModel extends yol\base\Model
    {
        public function rules()
        {
            return [
              'name|用户名'    => 'require|maxlen(20)',
              'email|用户邮箱' => 'require|email',
            ];
        }
    }
    ```
    独立使用验证器
    ```php
    $v = new \yol\validator\Validator();
    $v->validate($post, $rules);
    ```
6. 整合phpunit、phpdocumentor、composer
7. 提供swoole异步队列组件
8. MVC增加一层Bll业务逻辑层
    业务逻辑从控制器剥离出来，可以更好的测试代码和逻辑复用。
9. 定义错误处理方式
    使用异常处理错误，减少不必要的代码

##安装
目前只提供开发版本
clone github代码然后运行composer install


