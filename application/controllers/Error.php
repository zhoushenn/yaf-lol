<?php
/**
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 */
class ErrorController extends Yaf\Controller_Abstract {

	public function errorAction($exception) {
		$this->exception = $exception;

		if($this->exception instanceof Yaf\Exception){
			$this->frameError();			
		}else{
			$this->customerError();
		}
        return false;
	}

	public function frameError(){
		exit($this->exception);
	}

	public function customerError(){
		//线下环境走debugPlugin，不走这里。
        $code    = $this->exception->getCode();
        $message = sprintf("系统发生一个错误(code=%d)，请联系站长", $code);
        $this->getView()->message = $message;
    	$this->getView()->display('error/error.phtml');
	}

}
