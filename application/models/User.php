<?php
class UserModel extends Extend\base\ModelBase{

    public $tableName = 'prefix_xx'; //prefix会被配置的前缀替换掉

    public function getInfoByEmail($email){
        return $this->get([], ['email' => $email]);
    }

    public function setToken($userId, $token){
        return $this->update(['token' => $token], ['id' => $userId]);
    }


    public function getInfoByToken($token){
        return $this->get([], ['token' => $token]);
    }

}