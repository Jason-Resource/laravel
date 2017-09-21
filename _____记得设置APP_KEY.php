出现这个报错，是因为没有设置APP_KEY
.env文件

RuntimeException in Encrypter.php line 43:
The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.


生成KEY：
php artisan key:generate