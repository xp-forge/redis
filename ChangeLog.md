Redis protocol change log
=========================

## ?.?.? / ????-??-??

## 1.0.2 / 2021-10-24

* Made compatible with XP 11 - @thekid

## 1.0.1 / 2020-04-10

* Made compatible with `xp-forge/uri` version 2.0.0 - @thekid

## 1.0.0 / 2019-12-01

* Implemented xp-framework/rfc#334: Drop PHP 5.6. The minimum required
  PHP version is now 7.0.0!
  (@thekid)
* Made compatible with XP 10 - @thekid

## 0.3.0 / 2019-09-23

* Added `io.redis.RedisProtocol::socket()` accessor which returns the
  underlying socket, and can be used for *select* calls, e.g.
  (@thekid)

## 0.2.0 / 2019-08-24

* Merged PR #2: Redis CLI - @thekid
* Fixed zero-length reads - @thekid
* Merged PR #1: Pub/Sub - @thekid

## 0.1.0 / 2019-08-24

* Hello World! First release - @thekid