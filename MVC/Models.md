```php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserWarning extends Model
{
    //使用软删除
    use SoftDeletes;
    
    //链接数据库 \config\database.php -> connections
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
     * 小于ID 查询
     *
     * @author jilin
     */
    public function scopeLtIdQuery($query, $id)
    {
        return $query->whereIn('id', '<', $id);
    }

    /**
     * 小于等于ID 查询
     *
     * @author jilin
     */
    public function scopeLteIdQuery($query, $id)
    {
        return $query->whereIn('id', '<=', $id);
    }

    /**
     * 大于ID 查询
     *
     * @author jilin
     */
    public function scopeGtIdQuery($query, $id)
    {
        return $query->whereIn('id', '>', $id);
    }

    /**
     * 大于等于ID 查询
     *
     * @author jilin
     */
    public function scopeGteIdQuery($query, $id)
    {
        return $query->whereIn('id', '>=', $id);
    }

    /**
     * 不等于ID 查询
     *
     * @author jilin
     */
    public function scopeNeIdQuery($query, $id)
    {
        return $query->whereIn('id', '!=', $id);
    }
    
    /**
     * 标题 模糊查询
     *
     * @author jilin
     */
    public function scopeFuzzyTitleQuery($query, $keywords)
    {
        return $query->where('title', 'like', "%$keywords%");
    }

    /**
     * 包含代码 查询
     *
     * @author jilin
     */
    public function scopeCodeIncludeQuery($query,$arr)
    {
        $query->whereHas('relationAssembleChoice',function($query) use($arr){
            $query->whereIn('code',$arr);
        });
        return $query;
    }

    /***************************************关联模型**************************/
    public function relationAssembleChoice()
    {
        return $this->hasMany(AssembleChoice::class, 'assemble_id', 'id');
    }

}

```
