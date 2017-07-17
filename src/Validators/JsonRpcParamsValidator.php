<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Validators;


use JsonRpc\Exceptions\JsonRpcInvalidParamsException;

class JsonRpcParamsValidator implements ValidatorInterface
{
    public static function validate($payload)
    {
        if (is_array($payload) && (!in_array('params', $payload) || is_array($payload['params'])))
        {
            throw new JsonRpcInvalidParamsException();
        }
    }
}