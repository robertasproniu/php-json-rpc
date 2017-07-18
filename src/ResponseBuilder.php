<?php
/**
 * Created by: robert.asproniu
 */

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

    public function withId($id = null)
    {
        $this->id = $id;

        return $this;
    }

    public function withResult($result)
    {
        $this->result = $result;

        return $this;
    }

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

    public function build()
    {
        $response = $this->rpc;

        if ($this->error)
        {
            $response['error'] = $this->error;

            unset ($response['result']);
        }

        if ($this->result)
        {
            $response['result'] = $this->result;
        }

        if ($this->id)
        {
            $response['id'] = $this->id;
        }

        return $response;
    }
}