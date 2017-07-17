<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc;

use Closure;
use Exception;

class Server
{
    const VERSION = '2.0';
    /**
     * @var RequestHandler
     */
    private $requestHandler;
    /**
     * @var RouteHandler
     */
    private $handler;
    /**
     * @var ResponseHandler
     */
    private $responseHandler;
    /**
     * @var RouteHandler
     */
    private $routeResolver;

    function __construct(
        RequestHandler $requestHandler = null,
        RouteHandler $routeResolver = null,
        ResponseHandler $responseHandler = null
    ) {
        $this->responseHandler = $responseHandler;
        $this->requestHandler = $requestHandler;
        $this->routeResolver = $routeResolver;

        if (! $this->requestHandler)
        {
            $this->requestHandler = new RequestHandler();
        }

        if (! $this->routeResolver)
        {
            $this->routeResolver = new RouteHandler();
        }

        if (! $this->responseHandler)
        {
            $this->responseHandler = new ResponseHandler();
        }
    }

    /**
     * Register a route
     *
     * @param string $route
     * @param string|Closure $callback
     * @param string $method
     * @return $this
     */
    public function registerRoute($route, $callback, $method)
    {
        $this->routeResolver->registerRoute($route, $callback, $method);

        return $this;
    }


    /**
     * Run server
     *
     * @param string|null $payload
     */
    public function execute($payload = null)
    {
        try
        {
            $response = $this->parseRequest();
        }
        catch (Exception $e)
        {
            $response = $this->handleExceptions($e);
        }

        $this->responseHandler->sendHeaders();

        return $response;
    }

    private function parseRequest()
    {
        $results = $this->requestHandler
        ->withRouteHandler($this->routeResolver);

        return $this->responseHandler->success($results);
    }

    private function handleExceptions($e)
    {

    }
}