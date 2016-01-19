<?php
namespace yol\base;

use Yaf;

/**
 * 框架辅助类
 * @package yol\base
 */
class Helper
{
    /**
     * 生成指定路由类型url
     * 如果需要根据路由动态生成url，请调用Yaf_route_interface::assemble方法（注意这个assemble方法在低版本下有问题，参考github issue）
     *
     * @param $m
     * @param $c
     * @param $a
     * @param array $params
     */
    public static function url($c, $a = 'index', $m = 'default', array $params = [],  $routerType = 'default')
    {
        if($m == 'default'){
            $resquest = \Yaf\Dispatcher::getInstance()->getRequest();
            $m = strtolower($resquest->getModuleName());
        }

        /**
         * 默认静态路由方式
         */
        if($routerType == 'default'){
            $queryString = '';
            foreach($params as $key => $item){
                $queryString .= '/' . $key . '/' . $item;
            }
            return '/' . implode('/', [$m, $c, $a]) . $queryString;
        }
        //TODO: 按需求补全其它路由

        throw new \Yaf\Exception\RouterFailed('无效的路由类型'.$routerType.'无法生成URL');
    }


    /**
     * 获取ini配置
     * @param $configName 配置文件名称
     * @param $section 节点名称
     * @param $configPath 配置文件路径
     */
    public static function getConfig($configName, $section = null, $configPath = null)
    {
        if(!$configPath) $configPath = APPLICATION_PATH . '/conf';
        if(!$section) $section = \YAF\ENVIRON;

        if( Yaf\Registry::has($configName . $section) ){
            return Yaf\Registry::get($configName . $section);
        }

        $config = new Yaf\Config\Ini($configPath . '/' . $configName . '.ini', $section);
        Yaf\Registry::set($configName . $section, $config->toArray());
        return $config->toArray();
    }
}