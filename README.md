# exportcomments-php
Official PHP client for the ExportComments API. Export Social Media Comments from your PHP apps.

Autoload
--------

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


Usage examples
--------------

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
  "uniqueId": "dfd6a2f2-5579-421f-96ac-98993d0edea1",
  "date_created": "2016-08-26T11:44:09+00:00",
  "status": false,
  "error": null
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

[
  {
    "url": "https://www.instagram.com/p/1234567",
    "datecreated": "2016-08-26T07:32:09+00:00",
    "uniqueId": "dfd6a2f2-5579-421f-96ac-98993d0edea1",
    "done": true,
    "dateexported": "2016-08-26T07:32:27+00:00",
    "error": null,
    "total": 306,
    "totalExported": 306,
    "replies": true,
    "dateNotified": null,
    "repliesCount": 0,
    "downloadUrl": "/exports/comments5d638af93ab70-1234567.xlsx",
    "rawUrl": "/exports/6dbf1a87e0fb1f7f16b25be55bb37647-148d4d42-9db8-4e5a-9b51-a860e3646cb0.json"
  }
]

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
    "datecreated": Date,
    "uniqueId": Uuid,
    "done": Bool,
    "dateexported": Date,
    "error": String,
    "total": Int,
    "totalExported": Int,
    "replies": Bool,
    "dateNotified": Date,
    "repliesCount": Int,
    "downloadUrl": String,
    "rawUrl": String
  },
  {
    "url": String,
    "datecreated": Date,
    "uniqueId": Uuid,
    "done": Bool,
    "dateexported": Date,
    "error": String,
    "total": Int,
    "totalExported": Int,
    "replies": Bool,
    "dateNotified": Date,
    "repliesCount": Int,
    "downloadUrl": String,
    "rawUrl": String
  }
]

```