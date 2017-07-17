<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc\Exceptions;

use RuntimeException;
use Throwable;

class JsonRpcServerErrorException extends RuntimeException
{
   public function __construct()
   {
       $errors = [-32000, -32099];

       shuffle($errors);

       parent::__construct("Server error", array_shift($errors));
   }
}