<?php

class CollectionTest extends TestCase {

	public function testGeneralMethods() {
		$this->client->collection('scores')->remove();

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
		$filtering = $this->client->collection('scores')->where('name', 'Two')->get();
		$this->assertTrue(count($filtering) == 1);
		$this->assertTrue($filtering[0]['name'] == 'Two');

		$count = $this->client->collection('scores')->where('score', '<', '100')->count();
		$this->assertTrue($count == 3);

		$count = $this->client->collection('scores')->where('score', '<=', '100')->count();
		$this->assertTrue($count == 4);

	}

}
