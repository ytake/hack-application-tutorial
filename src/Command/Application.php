<?hh // strict

namespace Acme\Sample\Command;

use namespace HH\Lib\C;
use type Facebook\CLILib\CLIBase;
use type Facebook\CLILib\CLIWithRequiredArguments;
use namespace Facebook\CLILib\CLIOptions;

enum OutputFormat: string {
  PHP = 'php';
}

class Application extends CLIWithRequiredArguments {

  private ImmMap<OutputFormat, classname<AbstractCommand>> $m = ImmMap{
    OutputFormat::PHP => PhpCommand::class,
  };

  public static function getHelpTextForRequiredArguments(): vec<string> {
    return vec['command'];
  }

  <<__Override>>
  public async function mainAsync(): Awaitable<int> {
    $command = C\first($this->getArguments());
    if(OutputFormat::isValid($command)) {
      $commandClass = $this->m->at(OutputFormat::assert($command));
      await new $commandClass($this->getStdout())|> $$->runAsync();
      return 0;
    }
    $this->getStdout()->rawWriteBlocking('lol');
    return 1;
  }

  <<__Override>>
  protected function getSupportedOptions(): vec<CLIOptions\CLIOption> {
    return vec[];
  }
}
