# exportcomments-php

Official PHP client for the ExportComments API v3. Export comments and reviews from 40+ social media and review platforms.

## Requirements

- PHP 7.4 or higher
- JSON extension
- cURL extension

## Installation

### Using Composer (Recommended)

```bash
composer require exportcomments/exportcomments-php
```

### Manual Installation

```bash
git clone https://github.com/exportcomments/exportcomments-php.git
```

Then include the autoloader in your project:

```php
require_once 'path/to/exportcomments-php/autoload.php';
```

## Usage

```php
<?php
require_once 'vendor/autoload.php';

$export = new ExportComments\Client('<YOUR API KEY HERE>');
```

### Check API Connectivity

```php
$res = $export->ping();
var_dump($res->result); // ['message' => 'pong']
```

### Create Export

```php
$data = array(
    'url' => 'https://www.instagram.com/p/ABC123/',
    'replies' => false,
    'limit' => 100
);
$res = $export->exports->createExport($data);
$guid = $res->result['guid'];
```

### Check Export Status

```php
$res = $export->exports->checkExport($guid);
$status = $res->result['status']; // 'queueing', 'progress', 'done', 'error'
```

### Download Export File

```php
// Download the Excel/CSV file for a completed job
$filePath = $export->exports->downloadExport($guid);
echo "Saved to: $filePath";

// Or specify a custom output path
$filePath = $export->exports->downloadExport($guid, '/path/to/output.xlsx');
```

### Download Raw JSON Data

```php
$data = $export->exports->downloadJson($guid);
foreach ($data as $comment) {
    echo $comment['message'] . ' - ' . $comment['author_name'] . "\n";
}
```

### List Exports

```php
$res = $export->exports->listExports($page = 1, $limit = 10);
var_dump($res->result);
```

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

```php
try {
    $res = $export->exports->createExport($data);
} catch (ExportComments\ExportCommentsException $e) {
    echo "Error: " . $e->getMessage();
}
```

## Complete Example

```php
<?php
require_once 'vendor/autoload.php';

$export = new ExportComments\Client('<YOUR API KEY HERE>');

// Verify connectivity
$export->ping();

// Create export
try {
    $res = $export->exports->createExport([
        'url' => 'https://www.instagram.com/p/ABC123/',
        'options' => ['replies' => true, 'limit' => 100]
    ]);
} catch (ExportComments\ExportCommentsException $e) {
    die("Error: " . $e->getMessage());
}

$guid = $res->result['guid'];

// Poll until done
while (true) {
    $res = $export->exports->checkExport($guid);
    $status = $res->result['status'];

    if ($status === 'done') break;
    if ($status === 'error') die("Error: " . $res->result['error']);

    sleep(5);
}

// Download the file
$filePath = $export->exports->downloadExport($guid);
echo "Downloaded: $filePath\n";

// Or get raw JSON data
$data = $export->exports->downloadJson($guid);
echo "Got " . count($data) . " comments\n";
```

## Development

### Running Tests

```bash
composer test
```

### Code Style

```bash
composer cs-check
composer cs-fix
```

## License

MIT - see [LICENSE.txt](LICENSE.txt) for details.

## Support

- [Documentation](https://docs.exportcomments.com)
- [Issues](https://github.com/exportcomments/exportcomments-php/issues)
- [ExportComments Website](https://exportcomments.com)
