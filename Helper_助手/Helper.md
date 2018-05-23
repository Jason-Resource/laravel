```php

namespace App\Http\Common;

class Helper
{
    /**
     * 日志版本号
     * @param $build    boolean 是否创建版本
     * @notice:当为 true 是才生成一个新的版本，否则返回旧的版本号
     */
    public static function logVersion($build = false)
    {
        //不是话一个版本
        static $version = null;
        
        if($build == true){
            $version = self::uniqid();
        }
        
        return $version;
    }
    
    /**
     * 日志的频道
     * @param $channel  string  频道的名称
     */
    public static function logChannel($channel = null)
    {
        static $channel_name = '';
        
        if(!empty($channel)){
            $channel_name = $channel;
        }
        
        return $channel_name;
    }
    
    /**
     * 获取当前时间
     * @author  jianwei
     * @param   $flag   double  当$flag 为 true 时,等同于 time()
     */
    public static function getNow($flag = false)
    {
        static $now_time = null;
        if(null === $now_time){
            $now_time = date('YmdHis',time());
        }
        
        if(true === $flag){
            return date('YmdHis',time());
        }
        
        return $now_time;
    }
    
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
    
    /**
     * 验证参数
     *
     * @param $rule
     * @param $data
     * @throws JsonException
     */
    public static function validateParam($rule, $data)
    {
        $validator = app('validator')->make($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }
    }

    /**
     * 验证ID
     *
     * @param $id
     */
    public static function validateId($id)
    {
        $rule = ['id' => ['required', 'numeric', 'min:1']];
        $data = ['id' => $id];
        self::validateParam($rule, $data);
    }
    
    /**
     * 获取头部COOKIE
     * @param $url
     * @return mixed
     */
    public static function getCookieHeader($url) {
        // 初始化CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // 获取头部信息
        curl_setopt($ch, CURLOPT_HEADER, 1);
        //不验证证书下同
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 返回原生的（Raw）输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 执行并获取返回结果
        $content = curl_exec($ch);
        // 关闭CURL
        curl_close($ch);
        // 解析HTTP数据流
        if(empty($content)) {
            throw new JsonException(100002);
        }
        list($header, $body) = explode("\r\n\r\n", $content);

        return $header;
    }
}

```
