```php
<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;*/

class Test extends Model
{
    //使用软删除
    use SoftDeletes;

    /**
     * 链接数据库
     *
     * @var string
     * @notice \config\database.php -> connections
     */
    protected $connection = 'mysql';

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'stk_test';

    //指定主键
    protected $primaryKey = 'id';
    
    //指定允许批量赋值的字段
    /*
        $model->fill($params);
        $response = $model->save();
    */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    //指定不允许批量赋值的字段
     protected $guarded = [];

    //自动维护时间戳 如果是false 则不会自动插入 created_at 、 updated_at
    public $timestamps = false;

    //定制时间戳格式
    protected $dateFormat = 'U';

    /**
     * 避免转换时间戳为时间字符串
     */
    public function fromDateTime($value)
    {
        return $value;
    }
    //将默认增加时间转化为时间戳
    protected function getDateFormat()
    {
        return time();
    }
    
    /**
     * 在数组中可见的属性。
     *
     * @var array
     */
    protected $visible = ['id', 'first_name', 'age'];

    /**
     * 在数组中想要隐藏的属性。
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * 不用$model->first_name 这样调用一下，也会执行这个函数 ：getFirstNameAttribute()
     */
    protected $appends = ['first_name'];
    
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
     * 模糊查询
     *
     * @author jilin
     */
    public function scopeFuzzyNameQuery($query, $keywords)
    {
        return $query->where('first_name', 'like', "%$keywords%");
    }

    /**
     * 包含查询
     *
     * @author jilin
     */
    public function scopeEducationIncludeQuery($query, array $arr)
    {
        $query->whereHas('relationTestR',function($query) use($arr){
            $query->whereIn('education', $arr);
        });
        return $query;
    }

    /***************************************关联模型**************************/
    public function relationTestR()
    {
        return $this->hasMany(TestR::class, 'tid', 'id');
    }

    /***************************************访问器**************************/
    /**
     * 获取用户名字
     *
     * @param $value
     * @return string
     * @example $model->first_name
     */
    public function getFirstNameAttribute($value)
    {
        // 首字母大写
        return ucfirst($value);
    }

    /***************************************修改器**************************/
    /**
     * 设置用户名字。
     * 将传入的值全部转为小写
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }
    
    // 如果设置的值为空，则默认为NULL
    public function setWattBeginAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['watt_begin'] = null;
        }
    }
}

```
