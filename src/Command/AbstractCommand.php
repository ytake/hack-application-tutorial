<?hh // strict

namespace Acme\Sample\Command;

use namespace HH\Lib\Experimental\IO;

<<__ConsistentConstruct>>
abstract class AbstractCommand {

  public function __construct(
    protected IO\WriteHandle $write
  ) {}

  abstract public function runAsync(): Awaitable<void>;
}
