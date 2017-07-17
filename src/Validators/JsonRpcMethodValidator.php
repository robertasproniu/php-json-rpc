<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Validators;

use BadMethodCallException;

class JsonRpcMethodValidator implements ValidatorInterface
{
    public static function validate($payload)
    {
        if ( is_array($payload) && (!in_array('method', $payload) || empty( $payload['method'])) )
        {
            throw new BadMethodCallException();
        }
    }
}