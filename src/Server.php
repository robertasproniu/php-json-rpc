<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc;

use Closure;
use Exception;

class Server
{
    const RPC_VERSION = '2.0';
    /**
     * @var RequestHandler
     */
    private $requestHandler;
    /**
     * @var CallbackHandler
     */
    private $handler;
    /**
     * @var ResponseHandler
     */
    private $responseHandler;
    /**
     * @var CallbackHandler
     */
    private $callbackHandler;

    function __construct(
        RequestHandler $requestHandler = null,
        CallbackHandler $callbackHandler = null,
        ResponseHandler $responseHandler = null
    ) {
        $this->responseHandler = $responseHandler;
        $this->requestHandler = $requestHandler;
        $this->callbackHandler = $callbackHandler;

        if (! $this->requestHandler)
        {
            $this->requestHandler = new RequestHandler();
        }

        if (! $this->callbackHandler)
        {
            $this->callbackHandler = new CallbackHandler();
        }

        if (! $this->responseHandler)
        {
            $this->responseHandler = new ResponseHandler($this->requestHandler, $callbackHandler);
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
    public function withCallback($route, $callback, $method)
    {
        $this->callbackHandler->bindTo($route, $callback, $method);

        return $this;
    }

    /**
     * Run server
     *
     * @param string|null $payload
     */
    public function execute($payload = null)
    {
        return $this->responseHandler->respond($this->requestHandler, $this->callbackHandler);
    }

}