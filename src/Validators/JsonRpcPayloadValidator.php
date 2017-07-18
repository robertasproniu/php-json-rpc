<?php

namespace JsonRpc\Validators;

use JsonRpc\Contracts\ValidatorInterface;
use JsonRpc\Exceptions\JsonRpcParseErrorException;
use JsonRpc\Server;

class JsonRpcPayloadValidator implements ValidatorInterface
{
    public static function validate($payload)
    {
        if ( (isset($payload['method']) && ! is_string($payload['method']))
            || ! isset($payload['method'])
            || ! isset($payload['jsonrpc'])
            || (isset($payload['jsonrpc']) && $payload['jsonrpc'] != Server::RPC_VERSION)
            || (!isset($payload['jsonrpc']) && !is_array($payload['params']) )
        ) {
            throw new JsonRpcParseErrorException();
        }
    }
}