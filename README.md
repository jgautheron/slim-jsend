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
$app->add(new \SlimJsend\Middleware(array(
    'cors' => true,
)));

$app->get('/', function() use ($app) {
    // SlimJsend will automatically generate the proper response depending of the status code
    $app->render(200, ['Oh' => 'Hai!']);
});

$app->run();
```

## Credits
This project took inspiration from:
- https://github.com/dogancelik/slim-json
- https://github.com/entomb/slim-json-api