<?php

namespace ExportComments;

use ExportComments\ExportCommentsException;

class Request {

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

    function make_request($url, $method) {
        $options = array(
            'http' => array(
                'header' => "Content-type: application/json\r\n" .
                "X-AUTH-TOKEN: $this->token\r\n" .
                "User-Agent: php-sdk\r\n",
                'method' => $method,
//                'content' => json_encode($data),
                'ignore_errors' => true, // don't fail file_get_contents with status code 429
            ),
        );
        $context = stream_context_create($options);
        while (true) {
            $result = @file_get_contents($url, false, $context);
            $headers = $this->parseHeaders($http_response_header);
            $response_json = json_decode($result, true);
            if ($headers['response_code'] != 200 && $headers['response_code'] != 201) {
                throw new ExportCommentsException($response_json['detail']);
            } else {
                return array($response_json, $headers);
            }
        }
    }

}
