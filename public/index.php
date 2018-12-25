<?hh // strict

require_once __DIR__ . '/../vendor/hh_autoload.hh';

use type Acme\Sample\Application;
use type Acme\Sample\Container;
use type Ytake\Hungrr\ServerRequestFactory;
use namespace HH\Lib\Experimental\IO;

<<__EntryPoint>>
function main(): noreturn {
  
  list($read, $write) = IO\pipe_non_disposable();
  $app = new Application(new Container(), $read, $write);
  $app->run(ServerRequestFactory::fromGlobals(IO\request_input()));
  exit(0);
}
