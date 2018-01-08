### \bootstrap\app.php

```
Lumen (5.4.7)

$app->group(['namespace' => 'App\Http\Controllers', 'prefix'=>'api'], function ($app) {
    require __DIR__.'/../routes/api.php';
});

$app->group(['namespace' => 'App\Http\Controllers\V1', 'prefix' => 'api/v1'], function ($app) {
    require __DIR__.'/../routes/api_v1.php';
});
```

---

```
Lumen (5.5.2)

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

$app->router->group([
    'namespace' => 'App\Http\Controllers\Api\V1',
    'prefix'=>'api/v1',
], function ($router) {
    require __DIR__.'/../routes/api.php';
});
```
