<?hh // strict

namespace Acme\Sample\Middleware;

use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Ytake\HackHttpServer\MiddlewareInterface;
use type Ytake\HackHttpServer\RequestHandlerInterface;
use type Ytake\Hungrr\Response;
use type Ytake\Hungrr\StatusCode;

use namespace HH\Lib\Experimental\IO;

final class IndexAction implements MiddlewareInterface {
  
  public function __construct(
    private IO\WriteHandle $handle
  ) {}

  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    $this->handle->rawWriteBlocking('hello!');
    return new Response($this->handle, StatusCode::OK);
  }
}
