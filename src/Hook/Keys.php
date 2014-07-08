<?php
namespace Hook;

class Keys {
	protected $client;

	function __construct($client) {
		$this->client = $client;
	}

	public function get($key) {
		$entry = $this->client->get('key/' . $key);
		return isset($entry['value']) ? $entry['value'] : null;
	}

	public function set($key, $value) {
		return $this->client->post('key/' . $key, array('value' => $value));
	}

}
