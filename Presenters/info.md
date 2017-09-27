
```php
// 引入：
<?php $appPresenter = app('\App\Presenters\AppPresenter'); ?>
@inject('appPresenter','App\Presenters\AppPresenter')

// 使用：
{{ $appPresenter->activeMenuByController('CategoryController') }}
```
