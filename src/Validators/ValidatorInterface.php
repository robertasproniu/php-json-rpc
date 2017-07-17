<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Validators;

use Exception;

interface ValidatorInterface
{
    /**
     * @param $payload
     * @throws Exception
     */
    public static function validate($payload);
}