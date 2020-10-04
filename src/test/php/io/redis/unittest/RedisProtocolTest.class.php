<?php namespace io\redis\unittest;

use io\redis\RedisProtocol;
use peer\{AuthenticationException, ConnectException, ProtocolException};
use unittest\{Expect, Test, TestCase, Values};
use util\Secret;

class RedisProtocolTest extends TestCase {

  #[Test]
  public function can_create() {
    new RedisProtocol('redis://localhost');
  }

  #[Test]
  public function can_create_with_auth() {
    new RedisProtocol('redis://secret@localhost');
  }

  #[Test]
  public function endpoint() {
    $this->assertEquals('localhost:6379', (new RedisProtocol('redis://localhost'))->endpoint());
  }

  #[Test]
  public function endpoint_with_port() {
    $this->assertEquals('example.org:16379', (new RedisProtocol('redis://example.org:16379'))->endpoint());
  }


  #[Test]
  public function no_authentication() {
    $this->assertNull((new RedisProtocol('redis://localhost'))->authentication());
  }

  #[Test]
  public function authentication_via_connection_string() {
    $this->assertEquals(
      'secret',
      (new RedisProtocol('redis://secret@localhost'))->authentication()->reveal()
    );
  }

  #[Test, Values(eval: '["secret", new Secret("secret")]')]
  public function authentication_via_parameter($value) {
    $this->assertEquals(
      'secret',
      (new RedisProtocol('redis://localhost', $value))->authentication()->reveal()
    );
  }

  #[Test]
  public function connect() {
    $io= new Channel();
    $fixture= new RedisProtocol($io);

    $fixture->connect();
    $this->assertTrue($io->connected);
  }

  #[Test, Expect(ConnectException::class)]
  public function cannot_connect() {
    $io= new Channel(false);
    (new RedisProtocol($io))->connect();
  }

  #[Test]
  public function connect_returns_protocol_instance() {
    $io= new Channel();
    $fixture= new RedisProtocol($io);

    $this->assertEquals($fixture, $fixture->connect());
  }

  #[Test]
  public function initially_not_connected() {
    $io= new Channel();
    $fixture= new RedisProtocol($io);

    $this->assertFalse($io->connected);
  }

  #[Test]
  public function automatically_connects_if_necessary() {
    $io= new Channel("+OK\r\n");
    $fixture= new RedisProtocol($io);

    $fixture->command('ECHO', 'test');
    $this->assertTrue($io->connected);
  }

  #[Test]
  public function authenticate() {
    $io= new Channel("+OK\r\n");
    $fixture= new RedisProtocol($io, 'password');

    $fixture->connect();
    $this->assertTrue($io->connected);
  }

  #[Test]
  public function authentication_failure() {
    $io= new Channel("-ERR password incorrect\r\n");
    $fixture= new RedisProtocol($io, 'password');

    try {
      $fixture->connect();
      $this->fail('No exception raised', null, AuthenticationException::class);
    } catch (AuthenticationException $expected) {
      // OK
    }
    $this->assertFalse($io->connected);
  }

  #[Test]
  public function send() {
    $io= new Channel('');

    $bytes= "*2\r\n\$4\r\nECHO\r\n\$4\r\nTest\r\n";
    $fixture= new RedisProtocol($io);
    $fixture->send($bytes);
    $this->assertEquals($bytes, $io->out);
  }

  #[Test]
  public function receive() {
    $io= new Channel("*3\r\n\$7\r\nmessage\r\n\$7\r\nchannel\r\n\$4\r\ntest\r\n");

    $fixture= new RedisProtocol($io);
    $this->assertEquals(['message', 'channel', 'test'], $fixture->receive());
  }

  #[Test]
  public function set() {
    $io= new Channel("+OK\r\n");

    $result= (new RedisProtocol($io))->command('SET', 'key', 'value');
    $this->assertEquals("*3\r\n\$3\r\nSET\r\n\$3\r\nkey\r\n\$5\r\nvalue\r\n", $io->out);
    $this->assertEquals('OK', $result);
  }

  #[Test]
  public function exists() {
    $io= new Channel(":1\r\n");

    $result= (new RedisProtocol($io))->command('EXISTS', 'key');
    $this->assertEquals("*2\r\n\$6\r\nEXISTS\r\n\$3\r\nkey\r\n", $io->out);
    $this->assertEquals(1, $result);
  }

  #[Test]
  public function get() {
    $io= new Channel("\$5\r\nvalue\r\n");

    $result= (new RedisProtocol($io))->command('GET', 'key');
    $this->assertEquals("*2\r\n\$3\r\nGET\r\n\$3\r\nkey\r\n", $io->out);
    $this->assertEquals('value', $result);
  }

  #[Test]
  public function get_non_existant() {
    $io= new Channel("\$-1\r\n");

    $result= (new RedisProtocol($io))->command('GET', 'key');
    $this->assertEquals("*2\r\n\$3\r\nGET\r\n\$3\r\nkey\r\n", $io->out);
    $this->assertEquals(null, $result);
  }

  #[Test]
  public function get_empty_string() {
    $io= new Channel("\$0\r\n\r\n");

    $result= (new RedisProtocol($io))->command('GET', 'key');
    $this->assertEquals("*2\r\n\$3\r\nGET\r\n\$3\r\nkey\r\n", $io->out);
    $this->assertEquals('', $result);
  }

  #[Test]
  public function keys() {
    $io= new Channel("*2\r\n\$3\r\nkey\r\n\$5\r\ncolor\r\n");

    $result= (new RedisProtocol($io))->command('KEYS', '*');
    $this->assertEquals("*2\r\n\$4\r\nKEYS\r\n\$1\r\n*\r\n", $io->out);
    $this->assertEquals(['key', 'color'], $result);
  }

  #[Test, Expect(ProtocolException::class)]
  public function protocol_error() {
    $io= new Channel("-ERR unknown command\r\n");
    (new RedisProtocol($io))->command('NOT-A-REDIS-COMMAND');
  }
}