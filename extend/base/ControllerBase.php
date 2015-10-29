<?php
namespace Extend\base;

/**
 * 基类控制器，实现常用控制器方法，xss clean、设置布局 etc
 *
 * @package Extend\base
 * @author zhoushen <445484792@qq.com>
 * @since 1.0
 */
abstract class ControllerBase extends \Yaf\Controller_Abstract{

    /**
     * @var string 错误提示文件
     */
	public $showMsgTemplate = 'error/error.phtml';
    /**
     * @var string 布局文件
     */
	public $layout;

    /**
     * 设置布局
     * @param string $layout 布局文件
     */
	public function setLayout($layout){
		$this->getResPonse()->layout = function($content) use ($layout){
            return $this->getView()->render($layout, ['content'=>$content]);
        };
	}

    /**
     * ajax请求响应内容
     * @param int $code
     * @param string $msg
     * @param string $content
     */
	public function ajaxResponse($code = 1, $msg = 'success', $content = ''){
		exit(json_encode([
				'code' => $code,
				'msg'  => $msg,
				'content' => $content,
			]));
	}

    /**
     * 显示消息提示信息
     * @param $msg
     * @param null $toUrl
     * @param int $time
     * @return bool
     */
	public function showMsg($msg, $toUrl = null, $time = 3){

		$this->getView()->display($this->showMsgTemplate, 
									['message'=>$msg, 'toUrl'=>$toUrl, 'time'=>$time]
								);
		return false;
	}

    /**
     * @param null $name
     * @return mixed|null
     */
	public function get($name = null){
		//静态路由没有$_GET
		if(!$_GET){
			$_GET = $this->getRequest()->getParams();
		}

		if($name === null){
			return $this->xssClean($_GET);
		}

		if(isset($_GET[$name])){
			return $this->xssClean($_GET[$name]);
		}

		return null;
	}

    /**
     * @param null $name
     * @return mixed|null
     */
	public function post($name = null){
		if($name === null){
			return $this->xssClean($_POST);
		}

		if(isset($_POST[$name])){
			return $this->xssClean($_POST[$name]);
		}

		return null;
	}

    /**
     * @param $data
     * @return mixed
     */
    public function xssClean($data){
		if(is_array($data)){
			return filter_var_array($data, FILTER_SANITIZE_STRING);
		}else{
			return filter_var($data, FILTER_SANITIZE_STRING);
		}
	}
}