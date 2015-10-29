<?php
class IndexController extends CommonController
{

    public $layout = 'layout/test.phtml';

	public function indexAction()
    {

        return true;
        // return $this->showMsg('heheheh', '/index');
	}

    public function testcliAction(){
        print_r($this->get());
        return false;
    }

    public function signinAction()
    {      
        $userService = new Service\user\DbUserAuthenticate(new \UserModel);
        $post = $this->getRequest()->getPost();

        try{

            $userService->signin($post);
            $userService->login($post);
            
        }catch (\Exception $e){
            $this->ajaxResponse(-1, $e->getMessage());
        }

        $this->ajaxResponse(1, 'success', ['location'=>'/home/index']);

        return false;
    }

    public function loginAction()
    {

    }

    public function resetAction()
    {

    }

}
	