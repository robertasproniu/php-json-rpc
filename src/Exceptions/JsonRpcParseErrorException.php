<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Exceptions;


use RuntimeException;

class JsonRpcParseErrorException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Parse error",-32700);
    }
}