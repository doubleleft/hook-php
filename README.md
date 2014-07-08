hook-php
===

Hook PHP Client.

Different from JavaScript client, there is no callback. Every request is
synchronous.

How to use
---

```php
<?php
$hook = Hook\Client::configure(array(
  'app_id' => 1,
  'key' => '006f04b4f723c9920e259a746f9318be',
  'endpoint' => 'http://dl-api.dev/index.php/'
));

$hook->collection('scores')->create(array(
  'name' => 'Endel',
  'score' => 7
));
```

License
---

MIT. See license file.
