<?hh // strict

namespace Acme\Sample;

use namespace Acme\Sample\Exception;
use namespace HH\Lib\{C, Str};
type TCallable = (function(\Acme\Sample\Container): mixed);

class Container {
  private bool $lock = false;
  private dict<string, (Scope, TCallable)> $map = dict[];

  public function set<T>(
    classname<T> $id,
    TCallable $callback,
    Scope $scope = Scope::PROTOTYPE,
  ): void {
    if(!$this->lock) {
      $this->map[$id] = tuple($scope, $callback);
    }
  }

  <<__Rx>>
  protected function resolve<T>(classname<T> $id): mixed {
    if ($this->has($id)) {
      list($scope, $callable) = $this->map[$id];
      if ($callable is nonnull) {
        if ($scope === Scope::SINGLETON) {
          return $this->shared($id);
        }
        return $callable($this);
      }
    }
    throw new Exception\NotFoundException(
      Str\format('Identifier "%s" is not binding.', $id),
    );
  }

  <<__Rx>>
  public function get<T>(classname<T> $t): T {
    $mixed = $this->resolve($t);
    invariant($mixed instanceof $t, "invalid use of incomplete type %s", $t);
    return $mixed;
  }

  <<__Memoize>>
  protected function shared<T>(classname<T> $id): mixed {
    list($_, $callable) = $this->map[$id];
    return $callable($this);
  }

  <<__Rx>>
  public function has(string $id): bool {
    if($this->lock) {
      return C\contains_key($this->map, $id);
    }
    throw new Exception\ContainerNotLockedException(
      Str\format('Container was not locked.'),
    );
  }

  public function lock(): void {
    $this->lock = true;
  }

  public function unlock(): void {
    $this->lock = false;
  }
}
