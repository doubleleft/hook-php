<?php
require __DIR__ . '/../vendor/autoload.php';

class TestCase extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$app = json_decode(file_get_contents('tests/app.json'), true);
		$server_key = array_filter($app['keys'], function($key) {
			return $key['type'] == 'server';
		});
		$app_key = end($server_key);

		$this->client = Hook\Client::configure(array(
			'app_id' => $app_key['app_id'],
			'key' => $app_key['key'],
			'endpoint' => 'http://hook.dev/index.php/'
		));
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

}
