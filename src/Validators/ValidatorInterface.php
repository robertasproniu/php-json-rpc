<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Validators;

interface ValidatorInterface
{
    public static function validate($payload);
}