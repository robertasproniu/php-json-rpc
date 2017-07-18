<?php

namespace JsonRpc\Exceptions;

use JsonRpc\Contracts\CriticalExceptionInterface;
use RuntimeException;

class JsonRpcInvalidRequestException extends RuntimeException implements CriticalExceptionInterface
{
    public function __construct()
    {
        parent::__construct("Invalid Request", -32600);
    }
}