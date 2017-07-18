<?php

namespace JsonRpc\Validators;


use JsonRpc\Contracts\ValidatorInterface;
use JsonRpc\Exceptions\JsonRpcParseErrorException;

class JsonRpcPayloadIdValidator implements ValidatorInterface
{
    public static function validate($payload)
    {
        if ( !isset($payload['id']) )
        {
            throw new JsonRpcParseErrorException();
        }
    }
}