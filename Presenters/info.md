

### 引入：
```
<?php $appPresenter = app('\App\Presenters\AppPresenter'); ?>
或 
@inject('appPresenter','App\Presenters\AppPresenter')
```

### 使用：
```
{{ $appPresenter->activeMenuByController('CategoryController') }}
```
