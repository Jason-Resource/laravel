> **注意：** 以下DEMO是2.1版本的用法。
 
```php
composer require "maatwebsite/excel:2.1.28"
Maatwebsite\Excel\ExcelServiceProvider::class,
'Excel' => Maatwebsite\Excel\Facades\Excel::class,
php artisan vendor:publish
```
----

# 导入
```php

$excel_data[0] = [
    '客户名',
    '微信昵称'
];

foreach ($data as $item) {
    $excel_data[] = [
        $real_name,
        $nickname
    ];
}
        
Excel::create('数据统计', function ($excel) use ($excel_data) {

  $excel->sheet('score', function ($sheet) use ($excel_data) {
      $sheet->rows($excel_data);
      
      for ($i = 2; $i <= count($excel_data[0]) + 1; $i++) {
          $sheet->row($i - 1, function ($row) {
              $row->setAlignment('center');
              $row->setValignment('center');
          });
      }
                
      $sheet->setWidth(array(
          'A' => 12,
          'B' => 20,
          'C' => '100%',
      ));
  });

})->export('xls');
        
```

----

```php
\Excel::create('不适应', function($excel) use ($export_data) {
    $excel->sheet('Sheetname', function($sheet) use ($export_data) {
        $sheet->fromArray($export_data);
    });
})->save('xlsx');
```

----

```php

\Excel::create(storage_path('在线用户导出'), function($excel) use ($out) {

    $excel->sheet('Sheetname', function($sheet) use ($out) {

        $sheet->fromArray($out);

    });

})->export('xlsx');
```
 
----

# 导出

```php
$filePath = storage_path('test.xlsx');

DB::beginTransaction();
try {

    Excel::load($filePath, function($reader){
        $batch_insert_data = [];
        $data = $reader->all();
        foreach ($data as $key => $item) {
            if (empty($item)) {
                break;
            }

            $time = date('Y-m-d H:i:s');
            $name = trim($item['姓名']);
            $mobile = trim($item['微信号']);
            $qrcode = '';
            if (!empty($mobile)) {
                $qrcode = '/static/wechat/qrcode/'.$mobile.'.png';
            }
            $email = trim($item['信箱']);
            $email = str_replace('E-mail：', '', $email);

            if (empty($name)) {
                break;
            }

            $base_data = [
                'name' => $name,
                'type' => 1,
                'wechat' => $mobile,
                'mobile' => $mobile,
                'contact_phone' => trim($item['联系方式1']),
                'contact_phone2' => trim($item['联系方式2']),
                'supervise_phone' => trim($item['监督电话1']),
                'supervise_phone2' => trim($item['监督电话2']),
                'email' => $email,
                'live_link' => trim($item['直播室链接']),
                'qrcode' => $qrcode,
            ];

            // 查询是否存在
            $info = $this->marketing_business->detailByNameAndType($name);

            // 不存在 则 批量添加
            if (!isset($info['id'])) {
                $base_data['created_at'] = $time;
                $base_data['updated_at'] = $time;
                $batch_insert_data[] = $base_data;
            } else {
                // 存在 则 更新
                $this->marketing_business->update($info['id'], $base_data);
            }
        }

        app('MarketingModel')->insert($batch_insert_data);
    });

} catch (\Exception $e) {

    DB::rollBack();
    dd($e->getErrorMsg());
}
DB::commit();
```
