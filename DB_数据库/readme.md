```php

app('db')->connection('mysql_user');


//获取 最新新增ID
$db->getPdo()->lastInsertId();
```

```sql
SELECT DATE_FORMAT(`created_at`, '%Y-%m-%d') `days`,count(id) `count` FROM pf_monitor_data  where created_at<:end_time GROUP BY `days`
```
