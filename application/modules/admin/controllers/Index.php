<?php
class IndexController extends Yaf\Controller_Abstract {

	public function indexAction() {

		$helloModel = new Admin\models\Hello;
		$helloModel->foo();

	}
}
 