<?hh // strict

namespace Acme\Sample\Middleware;

use type Acme\Sample\Container;
use type Nazg\Heredity\Resolvable;
use type Ytake\HackHttpServer\MiddlewareInterface;

class ContainerResolver implements Resolvable {

  public function __construct(
    protected Container $container
  ) {}

  public function resolve(
    classname<MiddlewareInterface> $middleware
  ): MiddlewareInterface {
    return $this->container->get($middleware);
  }
}
