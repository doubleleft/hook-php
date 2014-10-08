<?php
require __DIR__ . '/../vendor/autoload.php';

class TestCase extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->client = Hook\Client::configure(array(
			'app_id' => 1,
			'key' => '5ddf09ad42863cee82e640645c27b1e4',
			'endpoint' => 'http://hook-stuff.dev/hook/public/index.php/'
		));
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

}
