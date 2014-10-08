<?php
namespace Hook;

class Keys {
	protected $client;

	function __construct($client) {
		$this->client = $client;
	}

	public function get($key) {
		return $this->client->get('key/' . $key);
	}

	public function set($key, $value) {
		return $this->client->post('key/' . $key, array('value' => $value));
	}

}
