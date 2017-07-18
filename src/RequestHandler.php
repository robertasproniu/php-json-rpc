<?php


namespace JsonRpc;

use JsonRpc\Validators\JsonRpcJsonFormatValidator;
use JsonRpc\Validators\JsonRpcPayloadIdValidator;
use JsonRpc\Validators\JsonRpcPayloadValidator;

class RequestHandler
{
    private $payload = null;

    /**
     * RequestHandler constructor.
     */
    public function __construct()
    {
        $this->processParsing();
    }

    /**
     * Set payload to request
     *
     * @param string|null $payload
     * @return $this
     */
    public function processPayload($payload = null)
    {
        $this->payload = $payload;

        $this->processParsing();

        return $this;
    }

    /**
     * Process request payload
     *
     * @return null
     */
    public function processRequest()
    {
        $this->processValidation($this->payload);

        return $this->payload;
    }

    /**
     * parse payload and trasnfor to array
     */
    private function processParsing()
    {
        if (! $this->payload)
        {
            $this->payload = file_get_contents("php://input");
        }

        $this->payload = json_decode($this->payload, true);
    }

    /**
     * Process payload validation
     *
     * @param $payload
     */
    private function processValidation($payload)
    {

        if (count($payload) == count($payload, COUNT_RECURSIVE))
        {
            $this->validatePayload($payload);
        }
        else
        {
            foreach ($payload as $payloadSingle)
            {
                $this->validatePayload($payloadSingle, true);
            }
        }
    }

    /**
     * Validate payload
     *
     * @param $payload
     * @param bool $strict
     */
    private function validatePayload($payload, $strict = false)
    {
        JsonRpcJsonFormatValidator::validate($payload);

        JsonRpcPayloadValidator::validate($payload);

        if ($strict)
        {
            JsonRpcPayloadIdValidator::validate($payload);
        }
    }
}