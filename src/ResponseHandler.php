<?php

namespace JsonRpc;


use Exception;

class ResponseHandler
{
    private $headers = [
        "Content-Type" => "application/json",
        "Connection" => "close"
    ];

    private $responseBuilder = null;

    /**
     * ResponseHandler constructor.
     * @param ResponseBuilder|null $responseBuilder
     */
    public function __construct(ResponseBuilder $responseBuilder = null)
    {
        $this->responseBuilder = $responseBuilder;

        if (! $this->responseBuilder )
        {
            $this->responseBuilder = new ResponseBuilder();
        }

        $this->sendHeaders();
    }

    private function sendHeaders()
    {
        foreach ($this->headers as $header => $value)
        {
            header(sprintf("%s: %s", $header, $value));
        }
    }

    private function sendStatus()
    {
        http_response_code(204);
    }

    public function processResponseWithSuccess($result, $id = null)
    {
        $responseBuilder = clone $this->responseBuilder;

        if (! $id)
        {
            $this->sendStatus();
        }

        return $responseBuilder->withId($id)->withResult($result)->build();
    }

    public function processResponseWithError($exception, $id = null)
    {
        $responseBuilder = clone $this->responseBuilder;

        return $responseBuilder->withId($id)->withError($exception)->build();
    }
}