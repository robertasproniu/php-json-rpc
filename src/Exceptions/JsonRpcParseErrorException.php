<?php

namespace JsonRpc\Exceptions;


use JsonRpc\Contracts\CriticalExceptionInterface;
use RuntimeException;

class JsonRpcParseErrorException extends RuntimeException implements CriticalExceptionInterface
{
    public function __construct()
    {
        parent::__construct("Parse error",-32700);
    }
}