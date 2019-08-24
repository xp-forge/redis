<?php namespace xp\redis;

use io\redis\RedisProtocol;
use peer\{ProtocolException, ConnectException, AuthenticationException};
use util\cmd\Console;

/**
 * Redis CLI
 *
 * - Connect to a Redis Server on localhost
 *   ```
 *   $ xp redis redis://localhost
 *   ```
 * - Connect to a password-protected server
 *   ```
 *   $ xp redis redis://secret@redis.server.local
 *   ```
 *
 * Type `quit` to end the REPL. Call with `rlwrap` to add readline
 * functionality.
 */
class Runner {

  public static function main($args) {
    if (empty($args)) {
      Console::writeLine('Usage: xp redis [connection]');
      return 2;
    }

    $proto= new RedisProtocol($args[0]);
    Console::writeLine('Connecting to ', $proto->endpoint());
    try {
      $proto->connect();
    } catch (ConnectException | AuthenticationException $e) {
      Console::$err->writeLine($e);
      return 1;
    }

    // Enter REPL
    $prompt= "\033[34;1m".$proto->endpoint()."\033[0m>";
    while (null !== ($line= Console::readLine($prompt))) {
      $line= trim($line);
      if ('' === $line) {
        continue;
      } else if ('quit' === $line || 'exit' === $line) {
        $proto->send('quit');
        break;
      } else if (0 === strncasecmp($line, 'subscribe', 9)) {
        Console::writeLine("\033[32;1mUse `poll` to wait for the next message\033[0m");
      }

      try {
        'poll' === $line || $proto->send($line);
        Console::writeLine($proto->receive());
      } catch (ProtocolException $e) {
        Console::$err->writeLine($e);
      }
    }

    return 0;
  }
}