<?php

namespace ExportComments;

use ExportComments\Config;
use ExportComments\Exports;

class Client {

    function __construct($token, $base_endpoint = Config::DEFAULT_BASE_ENDPOINT) {
        $this->token = $token;
        $this->exports = new Exports($token, $base_endpoint);
    }

}
