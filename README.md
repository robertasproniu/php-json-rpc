# PHP Simple JSON-RPC 2.0 server
### Installation

Use [Composer](https://getcomposer.org/)

```json
"require" : {
    "robertasproniu/php-json-rpc": "~1.0"
}
```

### Initialize
```php
require_once 'vendor/autoload.php';

use JsonRpc\Server;

$server = new Server();
```

### Define callbacks

```php
$server
    ->withCallback('add', function($a, $b) {
        return $a + b;
    })
    // OR 
    ->withCallback('substract', 'className', 'methodName');
```

### Define Middlewares
```php
$server
    ->withMiddleware(function($request, $response) {
        // add logic here
        
        return true; // will invalidate middleware
    }); 
```

### Run server
```php
$server->execute(); // return json
// OR
$server->execute($payload); // pass json payload 
```



