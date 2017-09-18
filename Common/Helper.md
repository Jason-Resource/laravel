```php

namespace App\Http\Common;

class Helper
{
    
    /**
     * 检查某个数组中是否有重复数据
     * 
     * @param   $arr    array   数组
     */
    public static function checkArrRepeat(array $arr)
    {
        return max(array_count_values($arr)) > 1 ? true : false;
    }

    /**
     * 返回随机码

     * @param   $length   int      随机码长度
     */
    public static function getRandomString($length = 10)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, ceil($length / strlen($pool)))), 0, $length);
    }

    /**
     * 返回加密后的密码
     * 
     * @param $password string 要加密的密码
     * @param $salt string 随机盐
     */
    public static function getEncryPwd($password, $salt)
    {
        return substr(md5($password), 7, 12) . substr(md5($password . $salt), 8, 12) . substr(md5($salt), 10, 12);
    }

    /**
     * 检查是否视频
     * 
     * @param $video_url
     * @return array|bool
     */
    public static function checkVideoUrl($video_url)
    {
        try {
            $url_header = get_headers($video_url, 1);
        }catch(\Exception $e){
            return false;
        }
        
        //判断是否200,
        if(!isset($url_header[0])){
            return flase;
        }
        
        if(!isset($url_header['Content-Type'])){
            return false;
        }
        
        if(!preg_match('/200/',$url_header[0])){
            return false;
        }
        
        /*
        $allow_Content_type = array('video/mp4','video','video/mpeg4');
        if(!in_array($url_header['Content-Type'],$allow_Content_type)){
            return false;
        }
        */
        if(!preg_match('/video/',$url_header['Content-Type'])){
            return false;
        }
        
        return $url_header;
    }

    /**
     * 更新拼装
     * $multipleData = [{"id":7,"sort":1},{"id":8,"sort":1}];
     * @param $tables
     * @param array $multipleData
     * @return bool|string
     * author hxc
     */
    public static function updateBatch($tables, $multipleData = array()){

        if(!empty($multipleData) ) {

            // column or fields to update
            $updateColumn = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0]; //e.g id
            unset($updateColumn[0]);
            $whereIn = "";
            $q = "UPDATE ".$tables." SET ";
            foreach ( $updateColumn as $uColumn ) {
                $q .=  $uColumn." = CASE ";

                foreach( $multipleData as $data ) {
                    $q .= "WHEN ".$referenceColumn." = ".$data[$referenceColumn]." THEN '".$data[$uColumn]."' ";
                }
                $q .= "ELSE ".$uColumn." END, ";
            }
            foreach( $multipleData as $data ) {
                $whereIn .= "'".$data[$referenceColumn]."', ";
            }
            $q = rtrim($q, ", ")." WHERE ".$referenceColumn." IN (".  rtrim($whereIn, ', ').")";
            return $q;

        } else {
            return false;
        }
    }

}

```
