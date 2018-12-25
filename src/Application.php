<?hh // strict

namespace Acme\Sample;

use type Acme\Sample\Router;
use type Acme\Sample\Container;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Nazg\Heredity\Heredity;
use type Nazg\Heredity\MiddlewareStack;

use namespace Facebook\HackRouter;
use namespace HH\Lib\Experimental\IO;
use namespace Acme\Sample\Middleware;

class Application {
  
  public function __construct(
    protected Container $container,
    private IO\ReadHandle $readHandle,
    private IO\WriteHandle $writeHandle
  ) {}

  public function run(ServerRequestInterface $request): void {
    $this->registerDependencies();
    $router = $this->container->get(HackRouter\BaseRouter::class);
    list($responder, $map) = $router->routeRequest($request);
    $stack = new MiddlewareStack(
      $responder['middleware'],
      new Middleware\ContainerResolver($this->container),
    );
    $queue = new Heredity($stack);
    $queue->handle($request);
    echo $this->readHandle->rawReadBlocking();
  }

  private function registerDependencies(): void {
    // routes
    $routes = ImmMap {
      HackRouter\HttpMethod::GET => ImmMap {
        '/' => shape(
          'middleware' => ImmVector {
            Middleware\IndexAction::class,
          },
        )
      }
    };
    $this->container->set(
      HackRouter\BaseRouter::class,
      ($container) ==> new Router($routes),
      Scope::SINGLETON
    );
    $this->container->set(
      Middleware\IndexAction::class,
      ($container) ==> new Middleware\IndexAction($this->writeHandle),
    );
    $this->container->lock();
  }
}
