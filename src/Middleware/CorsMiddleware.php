<?php

namespace App\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface {

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        // Calling $handler->handle() delegates control to the *next* middleware
        // In your application's queue.
        $response = $handler->handle($request);

        if ($response instanceof Response) {
            if ($request instanceof ServerRequest) {
                $response = $response
                    ->cors($request)
                    ->allowOrigin(['*'])
                    ->allowMethods(['*'])
                    ->allowHeaders(['*'])
                    ->allowCredentials()
                    ->build()
                    ->withStatus(200, __('You shall pass!!'));
            }
        }

        return $response;
    }
}

