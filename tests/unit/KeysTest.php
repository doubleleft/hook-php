<?php

class KeysTest extends TestCase {

	public function testGeneralMethods() {
		// get inexistent
		$doesnt_exists = $this->client->keys->get('doesnt_exists');
		$this->assertTrue($doesnt_exists === null);

		// get/set
		$this->client->keys->set('float', 5.5);
		$this->assertTrue($this->client->keys->get('float') === '5.5');
		$this->client->keys->set('integer', 500);
		$this->assertTrue($this->client->keys->get('integer') === '500');
		$this->client->keys->set('string', "Hello world!");
		$this->assertTrue($this->client->keys->get('string') === 'Hello world!');
	}

}

