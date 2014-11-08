<?php
namespace Hook;

class Client {
	protected static $instance;

	public $keys;
	protected $credentials;

	/**
	 * getInstance
	 * @return Hook\Client
	 */
	public static function getInstance() {
		return self::$instance;
	}

	/**
	 * configure
	 * @static
	 * @param array $credentials
	 */
	public static function configure($credentials = array()) {
		return self::$instance = new static($credentials);
	}

	/**
	 * __construct
	 * @param array $credentials
	 */
	function __construct($credentials = array()) {
		if (isset($credentials['url'])) { $credentials['endpoint'] = $credentials['url']; }

		// set default dl-api endpoint
		if (!isset($credentials['endpoint'])) {
			$credentials['endpoint'] = 'https://dl-api.heroku.com/';
		}

		$this->credentials = $credentials;
		$this->keys = new Keys($this);
	}

	/**
	 * collection
	 * @param string $name
	 */
	public function collection($name) {
		return new Collection(array(
			'name' => $name,
			'client' => $this
		));
	}

	/**
	 * get
	 * @param mixed $segments
	 * @param array $headers
	 */
	public function get($segments, $params = array(), $headers = array()) {
		return $this->request('get', $segments, $params, $headers);
	}

	/**
	 * remove
	 * @param mixed $segments
	 * @param array $headers
	 */
	public function remove($segments, $headers = array()) {
		return $this->request('delete', $segments, array(), $headers);
	}

	/**
	 * put
	 * @param mixed $segments
	 * @param array $data
	 * @param array $headers
	 */
	public function put($segments, $data = array(), $headers = array()) {
		return $this->request('put', $segments, $data, $headers);
	}

	/**
	 * post
	 * @param mixed $segments
	 * @param array $data
	 * @param array $headers
	 */
	public function post($segments, $data = array(), $headers = array()) {
		return $this->request('post', $segments, $data, $headers);
	}

	protected function request($method, $segments, $data = array(), $headers = array()) {
		$client = new \GuzzleHttp\Client();
		$method = strtoupper($method);
		$body = null;

		if ($method === "GET" && !empty($data)) {
			$segments .= '?' . urlencode(json_encode($data));

		} elseif ($method !== "GET" && !empty($data)) {
			$body = json_encode($data);
		}

		return $client->{$method}($this->credentials['endpoint'] . $segments, array(
			'headers' => $this->getHeaders(),
			'body' => $body,
			'exceptions' => false
		))->json();
	}

	protected function getHeaders($concat = array()) {
		return array_merge(array(
			'Content-Type' => 'application/json',
			'X-App-Id' => $this->credentials['app_id'],
			'X-App-Key' => $this->credentials['key'],
			'User-Agent' => 'hook-php'
		), $concat);

	}

}
