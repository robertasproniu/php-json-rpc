<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Exceptions;


use InvalidArgumentException;

class JsonRpcInvalidParamsException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Invalid params",-32602);
    }
}