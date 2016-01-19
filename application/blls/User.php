<?php
namespace bll;

/**
 * bll逻辑层提供服务，控制器通过调用逻辑层实现业务。
 *
 * Class User
 * @package bll
 */
class User
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new \UserModel();
    }

    /**
     * 把模型开放给控制器，尽量不要这么做
     * @return \UserModel
     */
    public function getUserModel()
    {
        return $this->userModel;
    }

    /**
     * 添加用户业务逻辑
     * @param $inputData
     * @return int
     */
    public function addUser($inputData)
    {
        //其他业务逻辑blabla...
        $result = $this->userModel->insert($inputData);
        if($result === false){
            throw new \LogicException('添加用户失败');
        }
        return $result;
    }
}