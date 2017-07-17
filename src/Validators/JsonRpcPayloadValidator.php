<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Validators;


use JsonRpc\Exceptions\JsonRpcInvalidRequestException;
use JsonRpc\Server;

class JsonRpcPayloadValidator implements ValidatorInterface
{

    public static function validate($payload)
    {
        if (!is_array($payload) ||
            !in_array('id', $payload) ||
            !in_array('method', $payload) ||
            !in_array('jsonrpc', $payload) ||
            $payload['jsonrpc'] != Server::VERSION ||
            !in_array('params', $payload)
        ) {
            throw new JsonRpcInvalidRequestException("Invalid RPC payload ");
        }
    }
}