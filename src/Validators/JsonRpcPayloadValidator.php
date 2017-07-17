<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Validators;

use JsonRpc\Exceptions\JsonRpcInvalidRequestException;
use JsonRpc\Exceptions\JsonRpcParseErrorException;
use JsonRpc\Server;

class JsonRpcPayloadValidator implements ValidatorInterface
{
    public static function validate($payload)
    {
        $payload = array_values($payload);

        if (!in_array('method', $payload)
            || ! is_string($payload['method'])
            || !in_array('jsonrpc', $payload)
            || $payload['jsonrpc'] != Server::RPC_VERSION
            || ( isset($payload['params']) && !is_array($payload['params']) )
        ) {
            throw new JsonRpcParseErrorException();
        }
    }
}