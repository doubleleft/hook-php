<?php
namespace Hook;

class Collection {

	/**
	 * name
	 * @var string
	 */
	protected $name;

	/**
	 * segments
	 * @var string
	 */
	protected $segments;

	/**
	 * client
	 * @var \Hook\Client
	 */
	protected $client;

	/**
	 * wheres
	 * @var array
	 */
	protected $wheres;

	/**
	 * options
	 * @var array
	 */
	protected $options;

	/**
	 * ordering
	 * @var array
	 */
	protected $ordering;

	/**
	 * group
	 * @var array
	 */
	protected $group;

	/**
	 * limit
	 * @var int
	 */
	protected $limit;

	/**
	 * offset
	 * @var int
	 */
	protected $offset;

	public function __construct($options) {
		$this->name = $options['name'];
		$this->client = isset($options['client']) ? $options['client'] : Client::getInstance();
		$this->segments = 'collection/' . $this->name;
		$this->reset();
	}

	protected function reset() {
		$this->wheres = array();
		$this->options = array();
		$this->ordering = array();
		$this->group = array();
		$this->limit = null;
		$this->offset = null;
		return $this;
	}

	public function create($data) {
		return $this->client->post($this->segments, $data);
	}

	public function where($field, $_operation = null, $_value = null, $operation = 'and') {
		$operation = (is_null($_value)) ? "=" : $_operation;
		$value = (is_null($_value)) ? $_operation : $_value;

		if (is_array($field)) {
			foreach($field as $field => $value) {
				if (is_array($value)) {
					$operation = $value[0];
					$value = $value[1];
				}
				$this->addWhere($field, $operation, $value, $operation);
			}
		} else {
			$this->addWhere($field, $operation, $value, $operation);
		}

		return $this;
	}

	public function orWhere($field, $_operation = null, $_value = null) {
		return $this->where($field, $_operation, $_value, 'or');
	}

	public function get() {
		return $this->client->get($this->segments, $this->buildQuery());
	}

	public function find($_id) {
		return $this->client->get($this->segments . '/' . $_id, $this->buildQuery());
	}

	public function select() {
		$this->options['select'] = func_get_args();
		return $this;
	}

	public function with() {
		$this->options['with'] = func_get_args();
		return $this;
	}

	public function group() {
		$this->group = func_get_args();
		return $this;
	}

	public function count($field = '*') {
		$this->options['aggregation'] = array('method' => 'count', 'field' => $field);
		return $this->get();
	}

	public function max($field) {
		$this->options['aggregation'] = array('method' => 'max', 'field' => $field);
		return $this->get();
	}

	public function min($field) {
		$this->options['aggregation'] = array('method' => 'min', 'field' => $field);
		return $this->get();
	}

	public function avg($field) {
		$this->options['aggregation'] = array('method' => 'avg', 'field' => $field);
		return $this->get();
	}

	public function sum($field) {
		$this->options['aggregation'] = array('method' => 'sum', 'field' => $field);
		return $this->get();
	}

	public function first() {
		$this->options['first'] = 1;
		return $this->get();
	}

	public function firstOrCreate($data) {
		$this->options['first'] = 1;
		$this->options['data'] = $data;
		return $this->client->post($this->segments, $this->buildQuery());
	}

	public function sort($field, $direction = null) {
		if (is_null($direction)) {
			$direction = 'asc';
		} else if (is_int($direction)) {
			$direction = (intval($direction) === -1) ? 'desc' : 'asc';
		}
		$this->ordering[] = array($field, $direction);
		return $this;
	}

	public function limit($int) {
		$this->limit = $int;
		return $this;
	}

	public function offset($int) {
		$this->offset = $int;
		return $this;
	}

	public function channel($options) {
		throw Exception("Not implemented.");
	}

	public function remove($_id = null) {
		$path = $this->segments;
		if (!is_null($_id)) {
			$path .= '/' . $_id;
		}
		return $this->client->remove($path, $this->buildQuery());
	}

	public function update($_id, $data = null) {
		if (is_null($data)) {
			return $this->updateAll($_id);
		} else {
			return $this->client->post($this->segments . '/' . $_id, $data);
		}
	}

	public function increment($field, $value) {
		$this->options['operation'] = array('method' => 'increment', 'field' => $field, 'value' => $value);
		return $this->client->put($this->segments, $this->buildQuery());
	}

	public function decrement($field, $value) {
		$this->options['operation'] = array('method' => 'decrement', 'field' => $field, 'value' => $value);
		return $this->client->put($this->segments, $this->buildQuery());
	}

	public function updateAll($data) {
		$this->options['data'] = $data;
		return $this->client->put($this->segments, $this->buildQuery());
	}

	protected function addWhere($field, $operation, $value) {
		$this->wheres[] = array($field, strtolower($operation), $value);
		return $this;
	}

	protected function buildQuery() {
		$query = array();

		// apply limit / offset
		if ($this->limit !== null) { $query['limit'] = $this->limit; }
		if ($this->offset !== null) { $query['offset'] = $this->offset; }

		// apply wheres
		if (count($this->wheres) > 0) {
			$query['q'] = $this->wheres;
		}

		// apply ordering
		if (count($this->ordering) > 0) {
			$query['s'] = $this->ordering;
		}

		// apply group
		if (count($this->group) > 0) {
			$query['g'] = $this->group;
		}

		$shortnames = array(
			'paginate' => 'p',
			'first' => 'f',
			'aggregation' => 'aggr',
			'operation' => 'op',
			'data' => 'data',
			'with' => 'with',
			'select' => 'select'
		);

		foreach($shortnames as $field => $shortname) {
			if (isset($this->options[$field])) {
				$query[$shortnames[$field]] = $this->options[$field];
			}
		}

		// clear wheres/ordering for future calls
		$this->reset();

		return $query;
	}
}
