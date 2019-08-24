Redis protocol
==============

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/redis.svg)](http://travis-ci.org/xp-forge/redis)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.6+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_6plus.png)](http://php.net/)
[![Supports PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/redis/version.png)](https://packagist.org/packages/xp-forge/redis)

[Redis](https://redis.io/) protocol implementation.

Example
-------

```php
use io\redis\RedisProtocol;

$protocol= new RedisProtocol('redis://localhost');
$protocol->command('SET', 'key', 'value');
$value= $protocol->command('GET', 'key');
``` 

The port defaults to 6379 and can be changed by adding it as follows: *redis://localhost:16379*. To use authentication, pass it as username in the connection string, e.g. *redis://secret@localhost*.