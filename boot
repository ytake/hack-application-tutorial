#!/usr/bin/env hhvm
<?hh // strict

require_once(__DIR__.'/vendor/hh_autoload.hh');

use type Acme\Sample\Command\Application;

<<__Entrypoint>>
function main(): void {
  Application::runAsync();
}
