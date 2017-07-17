<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Validators;


use Exception;
use JsonRpc\Exceptions\JsonRpcInvalidRequestException;

class JsonRpcJsonFormatValidator implements ValidatorInterface
{
    /**
     * @param $payload
     * @throws Exception
     */
    public static function validate($payload)
    {
        if (!is_array($payload))
        {
            throw new JsonRpcInvalidRequestException();
        }
    }
}