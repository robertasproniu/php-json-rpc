<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Validators;

use BadMethodCallException;

class JsonRpcMethodValidator implements ValidatorInterface
{
    public static function validate(array $payload)
    {
        if ( !in_array('method', $payload))
        {
            throw new BadMethodCallException();
        }
    }
}