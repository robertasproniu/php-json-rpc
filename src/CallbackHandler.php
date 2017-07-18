<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc;


use Closure;
use Exception;
use JsonRpc\Contracts\CriticalExceptionInterface;
use JsonRpc\Exceptions\JsonRpcInternalErrorException;
use JsonRpc\Exceptions\JsonRpcInvalidParamsException;
use JsonRpc\Exceptions\JsonRpcInvalidRequestException;
use JsonRpc\Exceptions\JsonRpcMethodNotFoundException;
use JsonRpc\Exceptions\JsonRpcServerErrorException;
use ReflectionClass;
use ReflectionFunction;

class CallbackHandler
{
    private $callbacks = [];

    private $middlewares = [];

    /**
     * @var RequestHandler
     */
    private $requestHandler;

    /**
     * @var ResponseHandler
     */
    private $responseHandler;

    /**
     * Register route
     *
     * @return CallbackHandler
     */

    public function bindCallback()
    {
        $args = func_get_args();

        if (count($args) < 2)
        {
            throw new JsonRpcServerErrorException();
        }

        if (is_callable($args[1]))
        {
            call_user_func_array([$this, 'registerCallback'], $args);
        }
        else
        {
            call_user_func_array([$this, 'registerCallbackWithClassMethod'], $args);
        }

        return $this;
    }

    public function bindMiddleware(Closure $callback)
    {
        $this->middlewares[] = $callback;
    }

    /**
     * Handle callback based on payload
     *
     * @param RequestHandler $requestHandler
     * @param ResponseHandler $responseHandler
     * @return array
     *
     */
    public function handle(RequestHandler $requestHandler, ResponseHandler $responseHandler)
    {
        $this->requestHandler = $requestHandler;

        $this->responseHandler = $responseHandler;

        return $this->processHandle();
    }

    /**
     * Register route with callback
     *
     * @param $routeName
     * @param Closure $callback
     */
    private function registerCallback($routeName, Closure $callback)
    {
        $this->callbacks[(string) $routeName] = $callback;
    }

    /**
     * Register route with class and method callback
     *
     * @param $routeName
     * @param string $className
     * @param string $methodName
     */
    private function registerCallbackWithClassMethod($routeName, $className, $methodName)
    {
        $this->callbacks[(string) $routeName] = [$className, $methodName];
    }

    /**
     * Execute callback function
     *
     * @param Closure $callback
     * @param array params
     * @return mixed
     */
    private function executeCallbackClosure(Closure $callback, array $params)
    {
        $reflection = new ReflectionFunction($callback);

        $this->validateRequiredParams($reflection->getNumberOfRequiredParameters(), $params);

        return $reflection->invokeArgs($params);
    }

    /**
     * @param string $class
     * @param string$method
     * @param array $params
     * @return mixed
     */
    private function executeCallbackClassMethod($class, $method, array $params)
    {
        if ( !class_exists($class) || empty($method))
        {
            throw new JsonRpcInternalErrorException();
        }

        $instance = (new ReflectionClass($class));

        if ( !$instance->hasMethod($method) )
        {
            throw new JsonRpcMethodNotFoundException();
        }

        $reflection = $instance->getMethod($method);

        $this->validateRequiredParams($reflection->getNumberOfRequiredParameters(), $params);

        return $reflection->invokeArgs($instance->newInstance(), $params);
    }


    /**
     * Validate no of required params
     *
     * @param $noParamsRequired
     * @param array $paramsSent
     * @throws JsonRpcInvalidParamsException
     */
    private function validateRequiredParams($noParamsRequired, array $paramsSent)
    {
        if ( (int) $noParamsRequired != count(array_values($paramsSent)) )
        {
            throw new JsonRpcInvalidParamsException();
        }
    }


    /**
     * Execute route callback and return results
     *
     * @param $procedure
     * @param array $parameters
     * @return mixed
     * @throws JsonRpcInternalErrorException
     */

    private function executeCallback($procedure, array $parameters = [])
    {
        if (! $this->callbacks[$procedure])
        {
            throw new JsonRpcMethodNotFoundException();
        }

        if (is_array($this->callbacks[$procedure]))
        {
            return $this->executeCallbackClassMethod($this->callbacks[$procedure][0], $this->callbacks[$procedure][1], $parameters);
        }

        if (is_callable($this->callbacks[$procedure]))
        {
            return $this->executeCallbackClosure($this->callbacks[$procedure], $parameters);
        }

        throw new JsonRpcInternalErrorException();
    }


    /**
     * Handle response
     *
     * @return array
     */
    private function processHandle()
    {
        try
        {
            $payload = $this->requestHandler->processRequest();

            $this->processMiddleware();

            return $this->processCallback($payload);
        }
        catch (CriticalExceptionInterface $exception)
        {
            return $this->responseHandler->processResponseWithError($exception, null);
        }
    }

    /**
     * Handle callbacks from payload
     *
     * @param array $payload
     * @return array
     */
    private function processCallback(array $payload = [])
    {
        if (count($payload) == count($payload, COUNT_RECURSIVE))
        {
            return $this->processSingleCallback($payload);
        }

        return $this->processBatchCallback($payload);
    }

    /**
     * Process multiple callbacks
     *
     * @param array $payloads
     * @return array
     */
    private function processBatchCallback(array $payloads)
    {
        $response = [];

        foreach ($payloads as $payload)
        {
            $response[] = $this->processSingleCallback($payload);
        }

        return $response;
    }

    /**
     * Process single callback
     *
     * @param array $payload
     * @return array
     */
    private function processSingleCallback(array $payload)
    {
        try
        {
            $result = $this->executeCallback($payload['method'], $payload['params']);

            return $this->responseHandler
                ->processResponseWithSuccess($result, isset($payload['id']) ? $payload['id'] : null);
        }
        catch (Exception $exception)
        {
            return $this->responseHandler->processResponseWithError($exception, $payload['id']);
        }
    }

    /**
     * Execute middlewares
     */
    private function processMiddleware()
    {
        foreach ($this->middlewares as $middleware)
        {
            try
            {
                $result = $middleware($this->requestHandler, $this->responseHandler);

                if ($result)
                {
                    throw new JsonRpcInvalidRequestException();
                }
            }
            catch (Exception $exception)
            {
                throw new JsonRpcServerErrorException();
            }
        }
    }
}