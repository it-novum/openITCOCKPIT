<?php
/* Container Test cases generated on: 2017-01-16 16:59:23 : 1484582363*/
App::import('controller', 'Container');

class TestContainercontroller extends Containercontroller {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ContainercontrollerTestCase extends CakeTestCase {
	function startTest() {
		$this->Container =& new TestContainercontroller();
		$this->Container->constructClasses();
	}

	function endTest() {
		unset($this->Container);
		ClassRegistry::flush();
	}

}
