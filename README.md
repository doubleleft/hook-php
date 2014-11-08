hook-php [![Build status](https://travis-ci.org/doubleleft/hook-php.svg?branch=master)](https://travis-ci.org/doubleleft/hook-php/)
===

PHP client for [hook](https://github.com/doubleleft/hook).

Different from JavaScript client, there is no callback. Every request is
synchronous.

How to use ([API Reference](http://doubleleft.github.io/hook-php))
---

```php
<?php
$hook = Hook\Client::configure(array(
  'app_id' => 1,
  'key' => '006f04b4f723c9920e259a746f9318be',
  'endpoint' => 'http://hook.dev/index.php/'
));

$hook->collection('scores')->create(array(
  'name' => 'Endel',
  'score' => 7
));
```

License
---

MIT
