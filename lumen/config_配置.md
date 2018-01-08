### 默认配置
```
\vendor\laravel\lumen-framework\config
```

---

### 加载所有配置
- 根目录创建config文件夹
- 将上面所有配置文件拷贝到/config下
- 编辑 \bootstrap\app.php
  ```
  //$app->configure('database');
  foreach (\Symfony\Component\Finder\Finder::create()->files()->name('*.php')->in($app->basePath('config')) as $file) {
      $filename = $file->getFileName();
      $place = strpos($filename,'.php');
      if($place > 0){
          $filename = mb_substr($filename,0,$place);
          $app->configure($filename);
      }
  }
  ```
