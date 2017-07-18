<?php

namespace JsonRpc;

use Exception;

class ResponseBuilder
{
    private $rpc = [
        'jsonrpc' => Server::RPC_VERSION
    ];

    private $id = null;

    private $result = null;

    private $error = null;

    /**
     * Set id
     *
     * @param null $id
     * @return $this
     */
    public function withId($id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set result
     *
     * @param $result
     * @return $this
     */
    public function withResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Set error
     *
     * @param Exception $exception
     * @param null $data
     * @return $this
     */
    public function withError(Exception $exception, $data = null)
    {
        $this->error = [
            'code'      => $exception->getCode(),
            'message'   => $exception->getMessage(),
        ];

        if ($data)
        {
            $this->error['data'] = $data;
        }

        return $this;
    }

    /**
     * Build response
     *
     * @return array
     */
    public function build()
    {
        $response = $this->rpc;

        $response['result'] = $this->result;

        if ($this->error)
        {
            $response['error'] = $this->error;

            unset ($response['result']);
        }

        if ($this->id)
        {
            $response['id'] = $this->id;
        }

        return $response;
    }
}