<?php
/**
 * Created by PhpStorm.
 * User: zhouwenlong_91
 * Date: 2015/10/13
 */
class HomeController extends CommonController{

    public function indexAction(){
        var_dump($this->userInfo);
        return false;
    }
}