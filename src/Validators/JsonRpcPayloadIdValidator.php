<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Validators;


use JsonRpc\Exceptions\JsonRpcParseErrorException;

class JsonRpcPayloadIdValidator
{
    public static function validate($payload)
    {
        if ( !isset($payload['id']) )
        {
            throw new JsonRpcParseErrorException();
        }
    }
}