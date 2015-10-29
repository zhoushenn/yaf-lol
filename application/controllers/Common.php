<?php
//通用基类，添加用户认证
abstract class CommonController extends Extend\base\ControllerBase{

	public $userInfo    = false; //用户登录信息

    public function init(){
        $this->auth();
        if($this->layout){
            $this->setLayout($this->layout);
        }
    }

	//用户认证
	protected function auth(){
        $userInfo = Service\user\DbUserAuthenticate::getUserBySession();
		if( $userInfo ){
			$this->userInfo = $userInfo;
            return true;
		}

        if( isset($_COOKIE['_token']) ){
            $this->userService = new Service\user\DbUserAuthenticate(new \UserModel);
            $this->userInfo = $this->userService->getUserByToken($_COOKIE['_token']);
            return true;
        }

        return false;

	}
}