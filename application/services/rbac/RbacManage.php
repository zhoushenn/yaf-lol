<?php
namespace Service\rbac;

class RbacManage{

	protected $rbac;

	public function __construct(\PhpRbac\Rbac $rbac){
		$this->rbac = $rbac;
	}

	public function getRbac(){
		return $this->rbac;
	}

	public function getRoleList(){
		$childs = $this->rbac->Roles->children(1);//root role is 1

		return array_merge( array([
				'ID' => 1,
				'Title' => 'root',
				'Description' => 'root',
			]), $childs);
	}	

	public function getPermissonList(){
		$childs = $this->rbac->Permissions->children(1);//root role is 1

		$permissionList = array_merge( array([
				'ID' => 1,
				'Title' => 'root',
				'Description' => 'root',
			]), $childs );

		// $return = [];
		// foreach ($permissionList as $key => $per) {
		// 	$group = explode('_', $per['Title'])[0];
		// 	$return[$group][] = $per;
		// }
		return $permissionList;
	}

	public function getRolePermisson(){

	}

}