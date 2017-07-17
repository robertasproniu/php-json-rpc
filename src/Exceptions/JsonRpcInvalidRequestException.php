<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Exceptions;

use RuntimeException;

class JsonRpcInvalidRequestException extends RuntimeException implements CriticalExceptionInterface
{
    public function __construct()
    {
        parent::__construct("Invalid Request", -32600);
    }
}