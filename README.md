# exportcomments-php

Official PHP client for the ExportComments API. Export Social Media Comments from your PHP apps.

## Requirements

- PHP 7.4 or higher
- JSON extension
- cURL extension

## Installation

### Using Composer (Recommended)

The recommended way to install the ExportComments PHP client is through [Composer](https://getcomposer.org/):

```bash
composer require exportcomments/exportcomments-php
```

### Manual Installation

If you don't want to use Composer, you can download the library and include the autoloader:

```bash
git clone https://github.com/exportcomments/exportcomments-php.git
```

Then include the autoloader in your project:

```php
require_once 'path/to/exportcomments-php/autoload.php';
```

## Usage examples

Here are some examples of how to use the library in order to create and use exports:

```php
<?php
require_once 'vendor/autoload.php'; // If using Composer
// require_once 'autoload.php'; // If using manual installation

// Use the API key from your account
$export = new ExportComments\Client('<YOUR API KEY HERE>');
```

### Create export

```php
// Create a new export
$data = array(
    'url' => 'https://www.facebook.com/post/123456789', 
    'replies' => false,
    'limit' => 100,
    'cookies' => array(
        'sessionid' => 'your_session_id'
    )
);
$res = $export->exports->createExport($data);
var_dump($res);
```

Response

```json
{
  "id": 123,
  "guid": "2cfb0b9d-7633-4341-a702-cb889fe549eb",
  "status": "queueing",
  "url": "https://www.instagram.com/p/1234567",
  "options": {
    "replies": false,
    "limit": 100
  }
}
```

### Checking export status

```php
// Check export by GUID
$guid = 'dfd6a2f2-5579-421f-96ac-98993d0edea1';
$res = $export->exports->checkExport($guid);
var_dump($res);
```

Response

```json
{
  "url": "https://www.instagram.com/p/1234567",
  "guid": "dfd6a2f2-5579-421f-96ac-98993d0edea1",
  "status": "done",
  "replies": false,
  "file_name": "comments5ea4b4d5a7602-1325511884314646.xlsx",
  "raw_file": "08b735760a5a40eb1fd70ca16e97aed3-2e0916fe-de86-4422-8449-fb608cbe5221.json",
  "total": 100,
  "total_exported": 98,
  "retry": 0,
  "error": null,
  "replies_count": 0,
  "timezone": "UTC",
  "created_at": "2016-08-26T07:32:27+00:00",
  "updated_at": "2016-08-26T07:32:27+00:00",
  "exported_at": "2016-08-26T07:35:12+00:00",
  "download_url": "/api/v3/job/dfd6a2f2-5579-421f-96ac-98993d0edea1/download"
}
```

### Download export files

```php
// Once the export status is "done", you can download files using the URLs from the response
$guid = 'dfd6a2f2-5579-421f-96ac-98993d0edea1';
$job = $export->exports->checkExport($guid);

// The response contains download_url and json_url for accessing the files
// You can use these URLs directly in your application or with file_get_contents()
if ($job->result['status'] === 'done') {
    $download_url = $job->result['download_url']; // Excel/CSV file
    $json_url = $job->result['json_url']; // Raw JSON data
    
    // Download the files using the provided URLs
    // Note: You'll need to include your API authentication when accessing these URLs
}
```

### Exports list

```php
// Get list of exports with pagination
$res = $export->exports->listExports($page = 1, $limit = 10);
var_dump($res);
```

Response

```json
[
  {
    "status": "done",
    "replies": false,
    "raw_file": "file.json",
    "locked": false,
    "timezone": "UTC",
    "options": {
      "limit": 10,
      "replies": false
    },
    "json_url": "/exports/file.json",
    "download_link": "/api/v3/job/guid/download"
  }
]
```

## API v3 Features

The v3 API introduces several improvements:

- **Job-based endpoints**: Use `/job` instead of `/export`
- **Enhanced options**: Support for VPN, proxy pools, cookies, and more
- **Better error handling**: More detailed error responses
- **Pagination**: Improved list endpoints with pagination support
- **Direct file access**: Download URLs provided in job response

### Advanced Export Options

```php
$data = array(
    'url' => 'https://twitter.com/user/status/123456789',
    'options' => array(
        'replies' => true,
        'limit' => 500,
        'vpn' => 'Norway',
        'cookies' => array(
            'auth_token' => 'your_twitter_auth_token'
        ),
        'minTimestamp' => 1622505600,
        'maxTimestamp' => 1625097600
    )
);
$res = $export->exports->createExport($data);
```

### Error Handling

The API v3 returns structured error responses:

```php
try {
    $res = $export->exports->createExport($data);
} catch (ExportComments\ExportCommentsException $e) {
    echo "Error: " . $e->getMessage();
    // Handle rate limits, invalid requests, etc.
}
```

### File Downloads

Once an export is complete (status = "done"), you can access the files using the URLs provided in the job response:

- `download_url`: Link to the formatted export file (Excel/CSV)
- `json_url`: Link to the raw JSON data

These URLs require proper authentication using your API token.

## Development

### Running Tests

```bash
composer test
```

### Code Style

Check code style:
```bash
composer cs-check
```

Fix code style:
```bash
composer cs-fix
```

### Static Analysis

```bash
composer analyse
```

## License

This project is licensed under the MIT License - see the [LICENSE.txt](LICENSE.txt) file for details.

## Support

- [Documentation](https://github.com/exportcomments/exportcomments-php)
- [Issues](https://github.com/exportcomments/exportcomments-php/issues)
- [ExportComments Website](https://exportcomments.com)
