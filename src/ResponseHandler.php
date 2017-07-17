<?php
/**
 * Created by: robert.asproniu
 */

namespace JsonRpc;


class ResponseHandler
{
    /**
     * Response headers
     *
     * @var array
     */

    protected $headers = [
        "Content-Type" => "application/json",
    ];

    public function sendHeaders()
    {
        foreach ($this->headers as $header => $value)
        {
            header(sprintf("%s: %s"), $header, $value);
        }

        return $this;
    }

    public function build()
    {
    }
}