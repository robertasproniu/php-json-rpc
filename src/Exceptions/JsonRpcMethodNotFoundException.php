<?php

namespace JsonRpc\Exceptions;


use BadMethodCallException;

class JsonRpcMethodNotFoundException extends BadMethodCallException
{
    public function __construct()
    {
        parent::__construct("Method not found",-32601);
    }
}