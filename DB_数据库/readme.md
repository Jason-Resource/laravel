```sql

app('db')->connection('mysql_user');


//获取 最新新增ID
$db->getPdo()->lastInsertId();
```
