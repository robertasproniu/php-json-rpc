<?php

namespace JsonRpc\Contracts;

use Exception;

interface ValidatorInterface
{
    /**
     * @param $payload
     * @throws Exception
     */
    public static function validate($payload);
}