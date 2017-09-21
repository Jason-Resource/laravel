```php
<?php

/*
|--------------------------------------------------------------------------
| 事务
|--------------------------------------------------------------------------
*/
DB::transaction(function () {
    DB::table('users')->update(['votes' => 1]);
    DB::table('posts')->delete();
});


DB::beginTransaction();
try {

    //do something...

} catch (JsonException $e) {
    
    DB::rollBack();
    throw new JsonException($e->getCode(), $e->getErrorMsg());
}
DB::commit();
```
