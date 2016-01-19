<?php
class IndexController extends yol\base\Controller
{
    /**
     * @var \bll\User
     */
    public $userBll;

    public function init()
    {
        parent::init();
        $this->userBll = new \bll\User();
    }

    public function indexAction()
    {
        return true;
    }

    public function addUserAction()
    {
        $post = $this->post();
        $this->userBll->addUser($post);
        return $this->showMsg('添加成功');
    }
}
