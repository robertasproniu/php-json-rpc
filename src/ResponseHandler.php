<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc;


use Exception;
use JsonRpc\Exceptions\CriticalExceptionInterface;

class ResponseHandler
{
    private $headers = [
        "Content-Type" => "application/json",
        "Connection" => "close"
    ];

    /**
     * @var RequestHandler null
     */
    private $requestHandler = null;

    /**
     * @var CallbackHandler
     */
    private $callbackHandler = null;

    /**
     * @var ResponseBuilder
     */
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
    }

    private function sendHeaders()
    {
        foreach ($this->headers as $header => $value) {
            header(sprintf("%s: %s"), $header, $value);
        }

        return $this;
    }

    public function respond(RequestHandler $requestHandler, CallbackHandler $callbackHandler)
    {
        $this->requestHandler = $requestHandler;

        $this->callbackHandler = $callbackHandler;

        return $this->processResponse();
    }

    /**
     * @return null
     */
    private function processResponse()
    {
        $response = null;

        try
        {
            $payload = $this->requestHandler->processRequest();

            $response = $this->callbackHandler->executeCallback($name);
        }
        catch (CriticalExceptionInterface $exception)
        {
            $response = $this->processResponseWithError($exception, null);
        }

        $this->sendHeaders();

        return $response;
    }

    private function processResponseWithSuccess($id = null, $result)
    {
        $responseBuilder = clone $this->responseBuilder;

        return $responseBuilder->withId($id)->withResult($result)->returnResponse();

    }

    private function processResponseWithError($exception, $id = null)
    {
        $responseBuilder = clone $this->responseBuilder;

        return $responseBuilder->withId($id)->withError($exception)->returnResponse();

    }
}