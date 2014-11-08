<?php

class CollectionTest extends TestCase {

	public function setUp() {
		parent::setUp();
		// remove possible previously created items
		try { $this->client->collection('scores'); } catch(\Exception $e) {}
	}

	public function testGeneralMethods() {

		// create
		$one = $this->client->collection('scores')->create(array(
			'name' => "One",
			'score' => 100
		));
		$this->assertTrue($one['name'] == "One");
		$this->assertTrue($one['score'] == 100);

		$two = $this->client->collection('scores')->create(array('name' => "Two", 'score' => 200));
		$three = $this->client->collection('scores')->create(array('name' => "Three", 'score' => 50));
		$four = $this->client->collection('scores')->create(array('name' => "Four", 'score' => 25));
		$five = $this->client->collection('scores')->create(array('name' => "Five", 'score' => 125));
		$six = $this->client->collection('scores')->create(array('name' => "Six", 'score' => 75));

		// filters
		$filtering = $this->client->collection('scores')->sort('name', 1)->where('name', 'Two')->get();
		$this->assertTrue(count($filtering) == 1);
		$this->assertTrue($filtering[0]['name'] == 'Two');

		$count = $this->client->collection('scores')->where('score', '<', '100')->count();
		$this->assertTrue($count == 3);

		$count = $this->client->collection('scores')->where('score', '<=', '100')->count();
		$this->assertTrue($count == 4);

		$this->client->collection('scores')->sort('score', -1)->updateAll(array('name' => "Top"));
		$updated = $this->client->collection('scores')->update($six['_id'], array('name' => "Six updated"));
		$this->assertTrue($updated['name'] == "Six updated");
	}

}
