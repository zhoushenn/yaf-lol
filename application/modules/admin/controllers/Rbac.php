<?php
class RbacController extends Yaf\Controller_Abstract{

	public function init(){
		$this->rbacManage  = new \Service\rbac\RbacManage(new \PhpRbac\Rbac);
	}

	public function indexAction(){

		$roleList = $this->rbacManage->getRoleList();
		$permissionList = $this->rbacManage->getPermissonList();
		$this->getView()->roleList = $roleList;
		$this->getView()->permissionList = $permissionList;

		return true;
	}

	public function testAction(){

		// $this->rbacManage->getRbac()->reset(true);

		$role = $this->rbacManage->Roles;
		$permisson = $this->rbacManage->Permissions;

		// $permisson->add('post_add', 'post_add_desc');
		// $permisson->add('post_delete', 'post_delete_desc');
		// $permisson->add('post_edit', 'post_edit_desc');
		// $permisson->add('post_view', 'post_view_desc');
		// $permisson->add('tag_delete', 'tag_delete_desc');
		// $permisson->add('tag_edit', 'tag_edit_desc');
		// $permisson->add('tag_view', 'tag_view_desc');

		var_dump( $permisson->children(1) );

		return false;
	}

}