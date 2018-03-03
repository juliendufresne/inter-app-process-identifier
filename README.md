Inter App Request Identifiers
=============================

Defines an interface to identify a process running between multiple applications.

When you work with multiple application calling each other, it is hard to follow which application calls which other application.  
Furthermore, when you have to see the logs of an application, you can not see where the request come from.

This library provides a solution:
* when an application calls another one, it adds some identifiers in the request header to keep track of who is the caller
* when an application receives a call from another one, it checks if the headers are set and will use them for further request
* it adds an extra section in your monolog logs containing:
  * an identification of the current application process
  * an identification of the caller who initiate the call to this application
  * an identification of the root caller who initiate the global call.

Installation
------------

```bash
composer require juliendufresne/inter-app-request-identifier
```

Example
-------

```php
use JulienDufresne\InterAppRequestIdentifier\Factory\Generator\RamseyUuidGenerator;
use JulienDufresne\InterAppRequestIdentifier\Factory\RequestIdFromConsoleFactory;
use JulienDufresne\InterAppRequestIdentifier\Factory\RequestIdFromRequestFactory;

$generator = new RamseyUuidGenerator();

$factory = RequestIdFromConsoleFactory($generator);
$requestIdentifier = $factory->create();

// or, if the current process is coming from the web

$factory = new RequestIdFromRequestFactory($generator, 'X-Root-Request-Id', 'X-Parent-Request-Id');
// will search for 'X-Root-Request-Id' and 'X-Parent-Request-Id' in $_SERVER array.
// Be careful that $_SERVEr prefix headers with HTTP_
// You might want to set headers to HTTP_X-Root-Request-Id
$requestIdentifier = $factory->create($_SERVER);
```

Generator
---------

Generator is used to generate unique request id for the current running application.  

This library provides one default generator: the [RamseyUuidGenerator](/src/Factory/Generator/RamseyUuidGenerator.php).  
If you want to use it, you must install the [`ramsey/uuid`](https://packagist.org/packages/ramsey/uuid) package.

You can define your own generator by implementing the [UniqueIdGeneratorInterface](/src/Factory/Generator/UniqueIdGeneratorInterface.php)

Guzzle
------

If you are using guzzle (package guzzlehttp/guzzle) to perform http requests, you can either add the RequestIdMiddleware to your handler stack:
 
```php
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use JulienDufresne\InterAppRequestIdentifier\Guzzle\RequestIdMiddleware;

$requestIdMiddleware = new RequestIdMiddleware(/* $requestIdentifier */);

$stack = HandlerStack::create();
$stack->push(Middleware::mapRequest($requestIdMiddleware));

$client = new Client(['handler' => $stack]);
```
 
 
or use our factory to create a guzzle client:

```php
use JulienDufresne\InterAppRequestIdentifier\Guzzle\ClientFactory;
use JulienDufresne\InterAppRequestIdentifier\Guzzle\RequestIdMiddleware;

$requestIdMiddleware = new RequestIdMiddleware(/* $requestIdentifier */);

$factory = new ClientFactory();
$client = $factory->create();
```

### Changing the headers sent

By default, sent headers are:
* `X-Root-Request-Id` for the root application identifier
* `X-Parent-Request-Id` for the current application identifier (that will become the parent application of the http request)

You can change this in the middleware:

```php
use JulienDufresne\InterAppRequestIdentifier\Guzzle\RequestIdMiddleware;

$requestIdMiddleware = new RequestIdMiddleware(
    /* $requestIdentifier */,
    'X-Root-Request-Id',
    'X-Parent-Request-Id'
);
```

Keep in mind that if you change this, you might want to change this in every applications

Monolog
-------

If you are using monolog to manage your application logs, you can use the [RequestIdentifierProcessor](/src/Monolog/RequestIdentifierProcessor.php):

```php
use JulienDufresne\InterAppRequestIdentifier\Monolog\RequestIdentifierProcessor;
use Monolog\Logger;

$processor = new RequestIdentifierProcessor(/* $requestIdentifier */);

$logger = new Logger('channel-name');
$logger->pushProcessor([$processor]);

$logger->addInfo('message');
```

### Changing the extra keys

By default, the processor will add a `request_id` array entry in the `extra` section with the following keys:
* `current` for the current application identifier
* `root` for the root application identifier
* `parent` for the parent application identifier

You can change this in the processor instantiation:

```php
use JulienDufresne\InterAppRequestIdentifier\Monolog\RequestIdentifierProcessor;

$processor = new RequestIdentifierProcessor(
    /* $requestIdentifier */,
    'request_id',
    'current',
    'root',
    'parent'
);
```
