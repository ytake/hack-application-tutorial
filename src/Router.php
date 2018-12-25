<?hh // strict

namespace Acme\Sample;

use namespace Facebook\HackRouter;
use type Facebook\Experimental\Http\Message\HTTPMethod;
use type Ytake\HackHttpServer\MiddlewareInterface;

type ImmRouteMap = ImmMap<HackRouter\HttpMethod, ImmMap<string, TResponder>>;
type MiddlewareVector = ImmVector<classname<MiddlewareInterface>>;
type TResponder = shape(
  'middleware' => MiddlewareVector
);

final class Router extends HackRouter\BaseRouter<TResponder> {

  public function __construct(
    private ImmRouteMap $routeMap
  ) {}

  <<__Override>>
  protected function getRoutes(
  ): ImmMap<HackRouter\HttpMethod, ImmMap<string, TResponder>> {
    return $this->routeMap;
  }
}
