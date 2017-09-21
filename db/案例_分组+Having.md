```php
<?php 
    /**
     * 用户聊天分组（以用户为主）
     *
     * @return void
     * @author chentengfeng @create_at 2016-12-27  23:41:18
     */
    public function chatUserRecordGroup($condition)
    {
        $where_chat = $having = '';

        //scope 用户ID
        if (isset($condition['user_id']) && !empty($condition['user_id'])) {
            $where_chat .= " AND history.user_id LIKE '%".$condition['user_id']."%' ";
        }

        //scope manage
        if (isset($condition['manage']) && !empty($condition['manage'])) {
            $where_chat .= " AND history.manage_id=".$condition['manage']." ";
        }

        if (!empty($condition['begin_time']) || !empty($condition['end_time'])) {
            $having = " having ";
        }
        //scope begin_time
        if (isset($condition['begin_time']) && !empty($condition['begin_time'])) {
            $having .= " min(created_at) >= '".$condition['begin_time']."' AND";
        }

        //scope end_time
        if (isset($condition['end_time']) && !empty($condition['end_time'])) {
            $having .= " max(created_at) <= '".$condition['end_time']."' AND";
        }
        $having = rtrim($having, 'AND');

        $query = "
            select 
                id,
                user_id,
                manage_id,
                message,
                start,
                min(created_at) start_time,
                max(created_at) as end_time,
                is_vistor,
                (select name from csa_user_chat_history where history.user_id=user_id and start='user' and history.is_vistor=is_vistor limit 1) as name
            from csa_user_chat_history as history
            where deleted_at is null
            $where_chat
            group by user_id, is_vistor
            $having
        ";

        return DB::select($query);
    }
?>
```
