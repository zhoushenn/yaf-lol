<?php
/**
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 */
class ErrorController extends yol\base\Controller {

	public function errorAction($exception) {
//        if(APP_DEBUG){
//            echo $exception;
//            return false;
//        }
		$code    = $exception->getCode();
        $message = $exception->getMessage();
		//框架层异常
		if($exception instanceof Yaf\Exception){

			$message = sprintf("发生一个系统错误(code=%d)，请联系站长", $code);
            $this->getView()->message = $message;
            return true;
		}elseif($exception instanceof \LogicException){ //bll层和model层异常
            return $this->showMsg($message);
		}else{

            $message = $exception;
            $this->getView()->message = $message;
            return true;
        }

	}
}
