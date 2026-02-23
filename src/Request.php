<?php

namespace ExportComments;

use ExportComments\ExportCommentsException;

class Request {

    public $token;
    public $endpoint;

    function parseHeaders($headers) {
        $head = array();
        foreach ($headers as $k => $v) {
            $t = explode(':', $v, 2);
            if (isset($t[1]))
                $head[trim($t[0])] = trim($t[1]);
            else {
                $head[] = $v;
                if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out))
                    $head['response_code'] = intval($out[1]);
            }
        }
        return $head;
    }

    function make_request($url, $method, $data = null, $retry_if_throttled = true) {
        $headers = "Content-type: application/json\r\n" .
                   "X-AUTH-TOKEN: $this->token\r\n" .
                   "User-Agent: php-sdk\r\n";

        $options = array(
            'http' => array(
                'header' => $headers,
                'method' => $method,
                'ignore_errors' => true,
            ),
        );

        if ($data !== null && ($method === 'POST' || $method === 'PUT')) {
            $options['http']['content'] = json_encode($data);
        }

        $context = stream_context_create($options);
        $retries_left = 3;

        while ($retries_left > 0) {
            $result = @file_get_contents($url, false, $context);
            $headers = $this->parseHeaders($http_response_header);
            $response_json = json_decode($result, true);

            if ($headers['response_code'] == 429 && $retry_if_throttled && $retries_left > 1) {
                $wait = isset($response_json['seconds_to_wait']) ? intval($response_json['seconds_to_wait']) : 2;
                sleep($wait);
                $retries_left--;
                continue;
            }

            if ($headers['response_code'] != 200 && $headers['response_code'] != 201) {
                $error_message = isset($response_json['error']) ? $response_json['error'] : 'Unknown error';
                throw new ExportCommentsException($error_message);
            }

            return array($response_json, $headers);
        }

        throw new ExportCommentsException('Max retries exceeded');
    }

    /**
     * Make a raw request that returns the response body as a string (for file downloads).
     */
    function make_raw_request($url, $method = 'GET') {
        $headers = "X-AUTH-TOKEN: $this->token\r\n" .
                   "User-Agent: php-sdk\r\n";

        $options = array(
            'http' => array(
                'header' => $headers,
                'method' => $method,
                'ignore_errors' => true,
            ),
        );

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        $headers = $this->parseHeaders($http_response_header);

        if ($headers['response_code'] != 200) {
            $response_json = json_decode($result, true);
            $error_message = isset($response_json['error']) ? $response_json['error'] : 'Download failed';
            throw new ExportCommentsException($error_message);
        }

        return $result;
    }

}
