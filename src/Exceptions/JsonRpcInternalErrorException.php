<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Exceptions;


use RuntimeException;

class JsonRpcInternalErrorException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Internal error",-32603);
    }
}