<?hh // strict

namespace Acme\Sample\Command;

use namespace HH\Lib\Experimental\IO;

class PhpCommand extends AbstractCommand {

  public async function runAsync(): Awaitable<void> {
    $write = $this->write;
    await $write->writeAsync('php');
    await $write->flushAsync();
  }
}
