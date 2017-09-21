引入：
@inject('appPresenter','App\Presenters\AppPresenter')

使用：
{{ $appPresenter->activeMenuByController('CategoryController') }}