# exportcomments-php

Official PHP client for the ExportComments API. Export Social Media Comments from your PHP apps.

## Autoload

The first step to use `exportcomments-php` is to download composer:

```bash
$ curl -s http://getcomposer.org/installer | php
```

Then we have to install our dependencies using:

```bash
$ php composer.phar install
```

Now we can use autoloader from Composer by:

```json
{
  "require": {
    "exportcomments/exportcomments-php": "~0.1"
  }
}
```

Or, if you don't want to use composer, clone the code and include this line of code:

    require 'autoload.php';

## Usage examples

Here are some examples of how to use the library in order to create and use exports:

```php
require 'autoload.php';

// Use the API key from your account
$export = new ExportComments\Client('<YOUR API KEY HERE>');

```

Create export

```php
// Create a new export
$data = array('url' => 'https://www.facebook.com/post/123456789', 'replies' => false, 'twitterType' => null);
$res = $export->exports->createExport($data);
var_dump($res);

```

Response

```json
{
  "code": 200,
  "success": true,
  "data": {
    "url": "https://www.instagram.com/p/1234567",
    "guid": "2cfb0b9d-7633-4341-a702-cb889fe549eb",
    "status": "done",
    "replies": false,
    "fileName": "comments5ea4b4d5a7602-1325511884314646.xlsx",
    "fileNameRAW": "08b735760a5a40eb1fd70ca16e97aed3-2e0916fe-de86-4422-8449-fb608cbe5221.json",
    "total": 100,
    "totalExported": 98,
    "retry": 0,
    "error": null,
    "repliesCount": 0,
    "twitterType": null,
    "timezone": "UTC",
    "createdAt": "2016-08-26T07:32:27+00:00",
    "updatedAt": "2016-08-26T07:32:27+00:00",
    "exportedAt": "",
    "downloadUrl": "/exports/comments5ea4b4d5a7602-1325511884314646.xlsx",
    "rawUrl": "/exports/08b735760a5a40eb1fd70ca16e97aed3-2e0916fe-de86-4422-8449-fb608cbe5221.json"
  },
  "message": null
}
```

Checking export status

```php
// Check export by uniqueId
$uniqueId = 'dfd6a2f2-5579-421f-96ac-98993d0edea1';
$res = $export->exports->checkExport($uniqueId);
var_dump($res);

```

Response

```json
{
  "code": 200,
  "success": true,
  "data": {
    "url": "https://www.instagram.com/p/1234567",
    "guid": "dfd6a2f2-5579-421f-96ac-98993d0edea1",
    "status": "queueing",
    "replies": false,
    "fileName": "comments5ea4b4d5a7602-1325511884314646.xlsx",
    "fileNameRAW": "08b735760a5a40eb1fd70ca16e97aed3-2e0916fe-de86-4422-8449-fb608cbe5221.json",
    "total": 0,
    "totalExported": 0,
    "retry": 0,
    "error": null,
    "repliesCount": 0,
    "twitterType": null,
    "timezone": "UTC",
    "createdAt": "2016-08-26T07:32:27+00:00",
    "updatedAt": "2016-08-26T07:32:27+00:00",
    "exportedAt": "",
    "downloadUrl": "/exports/comments5ea4b4d5a7602-1325511884314646.xlsx",
    "rawUrl": "/exports/08b735760a5a40eb1fd70ca16e97aed3-2e0916fe-de86-4422-8449-fb608cbe5221.json"
  },
  "message": null
}
```

Exports list

```php

$res = $export->exports->listExports();
var_dump($res);

```

Response

```json
[
  {
    "url": String,
    "createdAt": Date,
    "guid": Uuid,
    "status": String,
    "exportedAt": Date,
    "error": String,
    "total": Int,
    "totalExported": Int,
    "replies": Bool,
    "repliesCount": Int,
    "downloadUrl": String,
    "rawUrl": String
  },
  {
    "url": String,
    "createdAt": Date,
    "guid": Uuid,
    "status": String,
    "exportedAt": Date,
    "error": String,
    "total": Int,
    "totalExported": Int,
    "replies": Bool,
    "repliesCount": Int,
    "downloadUrl": String,
    "rawUrl": String
  }
]
```

```

```
