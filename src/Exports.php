<?php

namespace ExportComments;

use ExportComments\ExportCommentsResponse;
use ExportComments\ExportCommentsException;

class Exports extends Request
{
    protected $token;
    protected $endpoint;

    function __construct($token, $base_endpoint)
    {
        $this->token = $token;
        $this->endpoint = $base_endpoint;
    }

    function checkExport($guid)
    {
        $url = $this->endpoint . '/job/' . $guid;
        try {
            list($response, $header) = $this->make_request($url, 'GET');
        } catch (ExportCommentsException $ex) {
            throw $ex;
        }
        return new ExportCommentsResponse($response, array($header));
    }

    function createExport($data = array())
    {
        $url = $this->endpoint . '/job';
        
        $payload = array(
            'url' => $data['url']
        );
        
        // Build options array
        $options = array();
        
        // Handle legacy parameters
        if (isset($data['replies'])) {
            $options['replies'] = $data['replies'];
        }
        if (isset($data['twitterType']) && $data['twitterType'] !== null) {
            $options['tweets'] = true;
        }
        
        // Handle all supported options
        $supportedOptions = ['replies', 'tweets', 'limit', 'cookies', 'vpn', 'pool', 'cursor', 
                           'advanced', 'facebookAds', 'followers', 'following', 'id', 'likes', 
                           'live', 'maxTimestamp', 'minTimestamp', 'shares'];
        
        foreach ($supportedOptions as $option) {
            if (isset($data[$option])) {
                $options[$option] = $data[$option];
            }
        }
        
        // Also check if options were passed directly
        if (isset($data['options']) && is_array($data['options'])) {
            $options = array_merge($options, $data['options']);
        }
        
        if (!empty($options)) {
            $payload['options'] = $options;
        }

        try {
            list($response, $header) = $this->make_request($url, 'POST', $payload);
        } catch (ExportCommentsException $ex) {
            throw $ex;
        }
        return new ExportCommentsResponse($response, array($header));
    }

    function listExports($page = 1, $limit = 30)
    {
        $url = $this->endpoint . '/jobs';
        $query_params = http_build_query(array(
            'page' => $page,
            'limit' => $limit
        ));
        $url = $url . '?' . $query_params;
        
        try {
            list($response, $header) = $this->make_request($url, 'GET');
        } catch (ExportCommentsException $ex) {
            throw $ex;
        }
        return new ExportCommentsResponse($response, array($header));
    }
}
