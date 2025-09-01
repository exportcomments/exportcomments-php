<?php

namespace ExportComments;

use ExportComments\Config;
use ExportComments\Exports;

class Client
{

    protected $token;
    public $exports;

    function __construct($token, $base_endpoint = Config::DEFAULT_BASE_ENDPOINT)
    {
        $this->token = $token;
        $this->exports = new Exports($token, $base_endpoint);
    }

    /**
     * Ping the API to check connectivity
     * Uses v1 endpoint as per OpenAPI specification
     */
    public function ping()
    {
        $request = new Request();
        $request->token = $this->token;
        $request->endpoint = str_replace('/api/v3', '', $this->exports->endpoint);

        $url = $request->endpoint . '/api/v1/ping';

        try {
            list($response, $headers) = $request->make_request($url, 'GET');
            return new ExportCommentsResponse($response, array($headers));
        } catch (ExportCommentsException $ex) {
            throw $ex;
        }
    }
}
