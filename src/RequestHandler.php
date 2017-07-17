<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc;


use JsonRpc\Exceptions\JsonRpcInvalidParamsException;
use JsonRpc\Exceptions\JsonRpcInvalidRequestException;
use JsonRpc\Validators\JsonRpcPayloadValidator;

class RequestHandler
{

    public function withPayload($payload)
    {
        $payload = json_decode($payload);

        $jsonError = json_last_error();

        if ($jsonError || !JsonRpcPayloadValidator::validate($payload))
        {
            throw new JsonRpcInvalidRequestException("Invalid JSON-RPC payload");
        }

        $this->payload = $payload;
    }

    public function withRouteHandler($routeResolver)
    {

    }

    public function parse()
    {

    }
}