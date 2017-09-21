<?php 
    $condition = $request->all();

    $list_obj = Helper::listArrToObj($collection, ['path'=>Helper::action('Admin\TestController@list')]);

    $params = array_except($condition, ['_rd']);

    return view('admin.information.billboard-list', compact('list_obj', 'params'));

?>


@forelse ($list_obj as $item)
<tr>
    <td>
        {{ $item['id'] }}
    </td>
    <td>
        <button class="btn btn-primary del" data-id="{{$item['id']}}">移除</button>
        <input type="hidden" value="{{$item['id']}}" />
    </td>
</tr>
@empty
<tr>
    <td colspan="4">暂无数据</td>
</tr>
@endforelse


@if(isset($list_obj))
    {{$list_obj->appends($params)->render()}}
@endif