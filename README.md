Redis protocol
==============

[![Build status on GitHub](https://github.com/xp-forge/redis/workflows/Tests/badge.svg)](https://github.com/xp-forge/redis/actions)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.svg)](http://php.net/)
[![Supports PHP 8.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-8_0plus.svg)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/redis/version.svg)](https://packagist.org/packages/xp-forge/redis)

[Redis protocol](https://redis.io/topics/protocol) implementation.

Example
-------

```php
use io\redis\RedisProtocol;

$protocol= new RedisProtocol('redis://localhost');
$protocol->command('SET', 'key', 'value');

$value= $protocol->command('GET', 'key');
``` 

The port defaults to 6379 and can be changed by adding it as follows: *redis://localhost:16379*. To use authentication, pass it as username in the connection string, e.g. *redis://secret@localhost*.

Pub/Sub
-------

```php
use io\redis\RedisProtocol;

$protocol= new RedisProtocol('redis://localhost');
$protocol->command('SUBSCRIBE', 'messages');

while ($message= $protocol->receive()) {
  Console::writeLine('Received ', $message);
}
``` 
