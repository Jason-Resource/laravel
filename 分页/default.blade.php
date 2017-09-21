<!--
在其他要调用的界面引入就可以了
@include('client.pagination.default', ['paginator' => $category_list])

$category_list是在Controller中传递过来的
return view('list', compact('category_list'));
-->

@if($paginator && $paginator->lastPage()>1)
<div class="pagation">
    @if($paginator->currentPage() != 1)
        <a href="{{ ($paginator->currentPage() == 1) ? '#' : $paginator->url($paginator->currentPage()-1) }}">上一页</a>
    @endif

    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <a {{ ($paginator->currentPage() == $i) ? ' class=cur ' : '' }} href="{{ $paginator->url($i) }}">{{ $i }}</a>
    @endfor

    @if($paginator->currentPage() != $paginator->lastPage())
        <a href="{{ ($paginator->currentPage() == $paginator->lastPage()) ? '#' : $paginator->url($paginator->currentPage()+1) }}">下一页</a>
    @endif
</div>
@endif

/***************************************************************************************/

@if($paginator && $paginator->lastPage()>1)
<div class="pagation">
    @if($paginator->currentPage() != 1)
        <a href="{{ ($paginator->currentPage() == 1) ? '#' : $category_info->url_full.($paginator->currentPage()-1).'.html' }}">上一页</a>
    @endif

    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <a {{ ($paginator->currentPage() == $i) ? ' class=cur ' : '' }} href="{{$category_info->url_full.$i.'.html'}}">{{ $i }}</a>
    @endfor

    @if($paginator->currentPage() != $paginator->lastPage())
        <a href="{{ ($paginator->currentPage() == $paginator->lastPage()) ? '#' : $category_info->url_full.($paginator->currentPage()+1).'.html' }}">下一页</a>
    @endif
</div>
@endif


