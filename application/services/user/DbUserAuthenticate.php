<?php
namespace Service\user;
use Exception;
use Yaf;
use UserModel;

class DbUserAuthenticate implements UserInterface{

    protected $user;

    public function __construct(UserModel $user){
        $this->user = $user;
    }

    public function checkData(&$data, $sence='login'){
        $data['email']       = isset($data['name']) ? $data['name'] : '';
        $data['password']   = isset($data['password']) ? $data['password'] : '';
        if($sence == 'login'){
            $data['rememberme'] = isset($data['rememberme']) ? $data['rememberme'] : 2;
        }
        unset($data['name']);

        if(!$data['email']){
            throw new \InvalidArgumentException('请输入邮箱密码');
        }
        if(!$data['password']){
            throw new \InvalidArgumentException('请输入密码');
        }
    }


    public function signin($post){
        $this->checkData($post, 'signin');
        $post['password'] = md5($post['password']);
        if( false === $this->user->insert($post) ){
           throw new \Exception('注册失败');
        }
        return true;
    }

    public function login($post, $expire=3600){
        $this->checkData($post, 'login');
        $userinfo = $this->valid($post['email'], $post['password']);
        $session  = \Yaf\Session::getInstance();
        $session['userinfo'] = $userinfo; 

        if($post['rememberme'] == true){
            $token    = $this->generateToken($userinfo['name']);
            $this->setRemberMeToken($userinfo['id'], $token, time()+$expire);
        }
        
        return $userinfo;
    }

    public function valid($name, $password)
    {
        $userinfo = $this->user->getInfoByEmail($name);
        if( ! $userinfo || $userinfo['password'] != md5($password) ){
            throw new Exception('用户名或密码错误');
        }
        
        return $userinfo;
    }

    public function validByToken($token)
    {
        // TODO: Implement validByToken() method.
    }

    public static function getUserBySession(){
        $session = \Yaf\Session::getInstance();
        if( ! isset($session['userinfo']) ){
            return false;
        }
        return $session['userinfo'];
    }

    public function getUserByToken($token)
    {
        $userinfo  = $this->user->getInfoByToken($token);

        if(isset($userinfo)){
            $session  = \Yaf\Session::getInstance();
            $session['userinfo'] = $userinfo; 
            return $userinfo;
        }
        return false;
    }

    public function generateToken($name)
    {   
        return md5($name.time());
    }

    public function setRemberMeToken($userId, $token, $expire)
    {
        if( ! setcookie('_token', $token, $expire, '/') ){
           throw new Exception("无法设置cookie");
        }

        $result =  $this->user->setToken($userId, $token);
        if($result === false){
            $this->delRemberMeToken();
            throw new Exception("无法更新用户token");
        }

        return true;
    }

    public function delRemberMeToken()
    {
        return setcookie('_token', null, -1);
    }
}