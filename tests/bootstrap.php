<?php
require __DIR__ . '/../vendor/autoload.php';

class TestCase extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$app = json_decode(file_get_contents('tests/app.json'), true);
		$server_key = end(array_filter($app['keys'], function($key) {
			return $key['type'] == 'server';
		}));

		$this->client = Hook\Client::configure(array(
			'app_id' => $server_key['app_id'],
			'key' => $server_key['key'],
			'endpoint' => 'http://hook.dev/public/index.php/'
		));
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

}
