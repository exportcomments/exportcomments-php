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

// Create a new export
$data = array('url' => 'https://www.facebook.com/post/123456789', 'replies' => false, 'twitterType' => null);
$res = $export->exports->createExport($data);
var_dump($res);

// Check export by uniqueId
$uniqueId = 'dfd6a2f2-5579-421f-96ac-98993d0edea1';
$res = $export->exports->checkExport($uniqueId);
var_dump($res);


// List exports
$res = $export->exports->listExports();
var_dump($res);


```