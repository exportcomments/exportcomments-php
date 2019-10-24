<?php

namespace ExportComments;

use ExportComments\ExportCommentsResponse;
use ExportComments\ExportCommentsException;

class Exports extends Request {

    function __construct($token, $base_endpoint) {
        $this->token = $token;
        $this->endpoint = $base_endpoint;
    }

    function checkExport($uniqueId) {
        $url = $this->endpoint . '/export';
        $query_params = http_build_query(
                array('uniqueId' => $uniqueId)
        );
        $url = $url . '?' . $query_params;
        try {
            list($response, $header) = $this->make_request($url, 'GET', null);
        } catch (ExportCommentsException $ex) {
            throw $ex;
        }
        return new ExportCommentsResponse($response, array($header));
    }

    function createExport($data = array()) {
        $url = $this->endpoint . '/export';
        $params = array(
            'url' => $data['url'],
            'replies' => $data['replies']
        );
        if ($data['twitterType'] !== null) {
            $params['twitterType'] = $data['twitterType'];
        }
        $url = $url . '?' . http_build_query($params);
        try {
            list($response, $header) = $this->make_request($url, 'POST');
        } catch (ExportCommentsException $ex) {
            throw $ex;
        }
        return new ExportCommentsResponse($response, array($header));
    }

    function listExports() {
        $url = $this->endpoint . '/exports/me';
        try {
            list($response, $header) = $this->make_request($url, 'GET', null);
        } catch (ExportCommentsException $ex) {
            throw $ex;
        }
        return new ExportCommentsResponse($response, array($header));
    }

}
