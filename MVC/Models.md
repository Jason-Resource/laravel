```php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserWarning extends Model
{
    //使用软删除
    use SoftDeletes;
    
    /**
     * 链接数据库
     *
     * @var string
     * @notic \config\database.php -> connections
     */
    protected $connection = 'mysql';
    
    /**
     * 在数组中想要隐藏的属性。
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * 在数组中可见的属性。
     *
     * @var array
     */
    protected $visible = ['first_name', 'last_name'];

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'stk_user_warning';

    /***************************************常用查询条件**************************/

    /**
     * ID 查询
     *
     * @author jilin
     */
    public function scopeIdQuery($query,$id)
    {
        return $query->where('id', $id);
    }

    /**
     * ID 或查询
     *
     * @author jilin
     */
    public function scopeIdOrQuery($query, $id)
    {
        return $query->orWhere('id', $id);
    }

    /**
     * 包含ID 查询
     *
     * @author jilin
     */
    public function scopeIncludeIdQuery($query, array $id_arr)
    {
        return $query->whereIn('id', $id_arr);
    }

    /**
     * 排除ID 查询
     *
     * @author  jilin
     */
    public function scopeExcludeIdQuery($query, array $arr)
    {
        return $query->whereNotIn('id', $arr);
    }

    /**
     * 包含平台ID 查询
     *
     * @author  jilin
     */
    public function scopePlatformIncludeQuery($query, array $arr)
    {
      $query->whereHas('ArticlePlatformRelation',function($query) use($arr){
          $query->whereIn('platform_id',$arr);
      });
      return $query;
    }

    /***************************************常用查询条件**************************/


}

```
