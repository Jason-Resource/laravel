```php

$environment = app()->environment();

if (app()->environment('local')) {
    // 环境是 local
}

if (app()->environment('local', 'staging')) {
    // 环境是 local 或 staging...
}

```
