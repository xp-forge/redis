Redis protocol change log
=========================

## ?.?.? / ????-??-??

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