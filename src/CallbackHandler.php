<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc;


use Closure;
use JsonRpc\Exceptions\JsonRpcInternalErrorException;
use JsonRpc\Exceptions\JsonRpcInvalidParamsException;
use JsonRpc\Exceptions\JsonRpcMethodNotFoundException;
use JsonRpc\Exceptions\JsonRpcServerErrorException;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;

class CallbackHandler
{
    private $callbacks = [];

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
        if ( !class_exists($className) || empty($methodName) || !method_exists($className, $methodName))
        {
            throw new JsonRpcInternalErrorException();
        }

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
        $instance = (new ReflectionClass($class))->newInstance();

        $reflection = new ReflectionMethod($instance, $method);

        $this->validateRequiredParams($reflection->getNumberOfRequiredParameters(), $params);

        return $reflection->invokeArgs($instance, $params);
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
     * Register route
     *
     * @return CallbackHandler
     */

    public function bindTo()
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

    /**
     * Execute route callback and return results
     *
     * @param $route
     * @param array $parameters
     * @return mixed
     * @throws JsonRpcInternalErrorException
     */

    public function executeCallback($procedure, array $parameters = [])
    {
        if (! $this->callbacks[$procedure])
        {
            throw new JsonRpcMethodNotFoundException();
        }

        if (is_callable($this->callbacks[$procedure]))
        {
            return $this->executeCallbackClosure($this->callbacks[$procedure], $parameters);
        }

        if (is_array($this->callbacks[$procedure]))
        {
            return $this->executeCallbackClassMethod($this->callbacks[$procedure][0], $this->callbacks[$procedure][1], $parameters);
        }

        throw new JsonRpcInternalErrorException();
    }
}