# SlimJsend

JSend middleware for the Slim PHP framework.

## How to install
You can install SlimJsend with Composer by:
```
composer install jgautheron/slim-jsend
```
or adding this line to your `composer.json` file:
```
"jgautheron/slim-jsend": "dev-master"
```

## How to use
```php
require 'vendor/autoload.php';
$app = new \Slim\Slim();

// Add the middleware globally
$app->add(new \SlimJsend\Middleware([
    // true means *
    'cors' => true,
    // for a finer control over the allowed origin
    'cors' => 'https://foo.com'
]));

$app->get('/', function() use ($app) {
    // SlimJsend will automatically generate the proper JSend response depending of the status code
    $app->render(200, $messages); // success
    $app->render(500, $data); // fail
    
    // if an exception is thrown, it will be automatically converted to a JSend error message
    throw new Exception('Uh oh... missing username');
});

$app->run();
```

## Credits

- https://github.com/dogancelik/slim-json
- https://github.com/entomb/slim-json-api
- https://github.com/shkm/jsend
