<?php

$rule = [
    'article_id' => ['required','integer','min:1'],
];
$validator = app('validator')->make($data,$rule);
if ($validator->fails()) {
    throw new JsonException(10000, $validator->messages());
}
        
$rule = [
    'article_id' => ['required','integer','min:1'],
    'user_id' => ['sometimes','integer','min:1']
];
$validator = validator($data, $rule);
if($validator->fails()){
    throw new JsonException(10000, $validator->messages());
}

/***************************************************************/

$rule = [
    'id'=>['required','numeric'],
];
$message = [
    'required' => ':attribute不能为空',
    'numeric' => ':attribute必须是数字',
];
$attri = [
    'id' => 'ID',
];
$validator = validator(request()->all(), $rule, $message, $attri);
if ($validator->fails()) {
    dd($validator->getMessageBag()->messages());
}

/***************************************************************/

use Illuminate\Support\Facades\Validator;

$rules = array(
    'email' => 'required|email',
    'name' => 'required|between:1,20',
    'password' => 'required|min:8',
);
$message = array(
    "required" => ":attribute 不能为空",
    "between" => ":attribute 长度必须在 :min 和 :max 之间",
    "email" => "邮件格式不正确",
    "min" => "长度不能少于:min位数",
);
$attributes = array(
    "email" => '电子邮件',
    'name' => '用户名',
    'password' => '用户密码',
);

$validator = Validator::make(
    Input::all(),
    $rules,
    $message,
    $attributes
);
if ($validator->fails()) {
    $warnings = $validator->messages();
    $show_warning = $warnings->first();
    dd($show_warning);
}

//注：message和attribute可以在lang/(en`zh)/validation.php里设置

/***************************************************************/

$rule = array(
    'source' => [],
    'end_time' => ['size:14'],
    'category_id' => ['required'],
    'email' => ['required', 'email'],
    'sort' => ['required', 'numeric'],
    'sort' => ['required', 'integer'],
    'is_show' => ['required', 'in:0,1,2'],
    'permission_ids' => ['required', 'array'],
    'customize_url' => ['string', 'min:0', 'max:150',],
    'name' => ['required', 'regex:/[a-zA-Z\x{4e00}-\x{9fa5}]/u', 'max:' . $assemble_info_conf['max_len']], // 必须包含中英文，不允许只包含数字
    'phone'=> ['regex:/^13\d{9}$|^14\d{9}$|^15\d{9}$|^17\d{9}$|^18\d{9}$/'],
);

$validate = Validator::make($article_data, $rule);
if ($validate->fails()) {
    throw new JsonException(10000, $validate->messages());
}

/***************************************************************/

$validator = Validator::make(Request::all(), [
    'title' => 'required|unique:posts|max:255',
    'body' => 'required',
]);

if ($validator->fails()) {
    /*$err = $validator->messages();
    print_r($err);exit;*/
    return redirect('/')
        ->withErrors($validator)
        ->withInput();
}