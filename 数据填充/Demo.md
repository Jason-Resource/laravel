- 创建模型
  * php artisan make:model Test
  
  ```php
  namespace App;

  use Illuminate\Database\Eloquent\Model;

  class Test extends Model
  {
      protected $table = 'test';
  }
  ```
