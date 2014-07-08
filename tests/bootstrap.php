<?php
require __DIR__ . '/../vendor/autoload.php';

class TestCase extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->client = Hook\Client::configure(array(
			'app_id' => 1,
			'key' => '006f04b4f723c9920e259a746f9318be',
			'endpoint' => 'http://dl-api.dev/index.php/'
		));
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

}
