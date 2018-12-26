<?hh // strict

namespace Acme\Sample\Command;

use type Facebook\CLILib\CLIBase;
use namespace Facebook\CLILib\CLIOptions;

class Application extends CLIBase {
  <<__Override>>
  public async function mainAsync(): Awaitable<int> {
    $this->getStdout()->rawWriteBlocking("Hello, world!");
    return 0;
  }

  <<__Override>>
  protected function getSupportedOptions(): vec<CLIOptions\CLIOption> {
	  return vec[];
  }
}
