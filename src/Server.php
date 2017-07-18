<?php

namespace JsonRpc;

use Closure;

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
            $this->responseHandler = new ResponseHandler();
        }
    }

    /**
     * Register a procedure name with a callback
     *
     * @param string $name
     * @param string|Closure $callback
     * @param string $method
     * @return $this
     */
    public function withCallback($name, $callback, $method = null)
    {
        $this->callbackHandler->bindCallback($name, $callback, $method);

        return $this;
    }

    /**
     * Register a middleware callback
     *
     * @param Closure $callback
     */
    public function withMiddleware(Closure $callback)
    {
        $this->callbackHandler->bindMiddleware($callback);
    }

    /**
     * Run server
     *
     * @param string $payload
     *
     * @return mixed
     */
    public function execute($payload = null)
    {
        $this->requestHandler->processPayload($payload);

        $response = $this->callbackHandler->handle($this->requestHandler, $this->responseHandler);

        return json_encode($response);
    }

}