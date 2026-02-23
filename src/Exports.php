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

    /**
     * Download the export file for a completed job.
     *
     * @param string $guid The job GUID.
     * @param string|null $outputPath File path to save to. If null, uses the server-provided filename.
     * @return string The file path where the export was saved.
     * @throws ExportCommentsException
     */
    function downloadExport($guid, $outputPath = null)
    {
        $job = $this->checkExport($guid);

        $downloadUrl = isset($job->result['download_url']) ? $job->result['download_url'] : null;
        if (!$downloadUrl) {
            $downloadUrl = isset($job->result['download_link']) ? $job->result['download_link'] : null;
        }
        if (!$downloadUrl) {
            throw new ExportCommentsException('No download URL available for job ' . $guid . '. Status: ' . ($job->result['status'] ?? 'unknown'));
        }

        $content = $this->make_raw_request($downloadUrl);

        if ($outputPath === null) {
            $filename = isset($job->result['file_name']) ? $job->result['file_name'] : 'export-' . $guid . '.xlsx';
            $outputPath = getcwd() . '/' . $filename;
        }

        file_put_contents($outputPath, $content);

        return $outputPath;
    }

    /**
     * Download the raw JSON data for a completed job.
     *
     * @param string $guid The job GUID.
     * @return array The parsed JSON data (list of comments/reviews).
     * @throws ExportCommentsException
     */
    function downloadJson($guid)
    {
        $job = $this->checkExport($guid);

        $jsonUrl = isset($job->result['json_url']) ? $job->result['json_url'] : null;
        if (!$jsonUrl) {
            throw new ExportCommentsException('No JSON URL available for job ' . $guid . '. Status: ' . ($job->result['status'] ?? 'unknown'));
        }

        $content = $this->make_raw_request($jsonUrl);

        return json_decode($content, true);
    }
}
