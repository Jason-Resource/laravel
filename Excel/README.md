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
      $sheet->setWidth(array(
          'A' => 12,
          'B' => 20
      ));
  });

})->export('xls');
        
```
