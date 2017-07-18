<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Exceptions;

use JsonRpc\Contracts\CriticalExceptionInterface;
use RuntimeException;

class JsonRpcServerErrorException extends RuntimeException implements CriticalExceptionInterface
{
   public function __construct()
   {
       $errors = [-32000, -32099];

       shuffle($errors);

       parent::__construct("Server error", array_shift($errors));
   }
}