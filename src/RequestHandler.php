<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc;


use JsonRpc\Validators\JsonRpcJsonFormatValidator;
use JsonRpc\Validators\JsonRpcMethodValidator;
use JsonRpc\Validators\JsonRpcPayloadValidator;

class RequestHandler
{
    private $payload = [];

    private $routeHandler = null;

    private $responseBuilder;

    public function __construct()
    {
        $this->parsePayload();
    }

    public function withPayload($payload = null)
    {
        $this->payload = $payload;

        return $this;
    }

    public function withRouteHandler(CallbackHandler $routeHandler)
    {
        $this->routeHandler = $routeHandler;

        return $this;
    }

    public function withResponseBuilder(ResponseBuilder $responseBuilder)
    {
        $this->responseBuilder = $responseBuilder;
    }

    public function processRequest()
    {
        $this->validatePayload();

        return $this->payload;
    }

    private function parsePayload()
    {
        $this->payload = $this->payload ? $this->payload : file_get_contents("php://input");

        $this->payload = json_decode($this->payload, true);
    }

    private function validatePayload()
    {
        JsonRpcJsonFormatValidator::validate($this->payload);

        JsonRpcPayloadValidator::validate($this->payload);

        JsonRpcPayloadValidator::validate($this->payload);

        JsonRpcMethodValidator::validate($this->payload);
    }

    private function executeRoutes()
    {
        $results = [];

        if (count($this->payload) != count($this->payload, COUNT_RECURSIVE))
        {

        }

        return $results;
    }
}