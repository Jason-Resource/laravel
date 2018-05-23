@inject('paginate','App\Presenters\PaginatePresenter')

@if($paginator && $paginator->lastPage()>1)
    <div class="list-page">
        <ul class="pagination">
            @if($paginator->currentPage() != 1)
                <li>
                    <a href="{{ ($paginator->currentPage() == 1) ? '###' : $paginate->getCategoryPageUrl($category_info->url_full ,1) }}">
                        首页
                    </a>
                </li>

                <li style="width:60px;">
                    <a href="{{ ($paginator->currentPage() == 1) ? '###' : $paginate->getCategoryPageUrl($category_info->url_full ,($paginator->currentPage()-1)) }}">
                        上一页
                    </a>
                </li>
            @endif

            @for ($i = ($paginator->currentPage()-3); $i <= (($paginator->currentPage() + 3) + 1); $i++)
                @if($i>0 && ($i <= $paginator->lastPage()))
                    @if($i == $paginator->currentPage())
                        <li>
                            <a href="#" class="cur">
                                {{ $i }}
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{$paginate->getCategoryPageUrl($category_info->url_full ,$i)}}" {{ ($paginator->currentPage() == $i) ? ' class=cur ' : '' }}>
                                {{ $i }}
                            </a>
                        </li>
                    @endif
                @endif
            @endfor

            @if($paginator->currentPage() != $paginator->lastPage())
                <li style="width:60px;">
                    <a href="{{ ($paginator->currentPage() == $paginator->lastPage()) ? '###' : $paginate->getCategoryPageUrl($category_info->url_full ,($paginator->currentPage()+1)) }}">
                        下一页
                    </a>
                </li>
                <li>
                    <a href="{{ ($paginator->currentPage() == $paginator->lastPage()) ? '###' : $paginate->getCategoryPageUrl($category_info->url_full ,($paginator->lastPage())) }}">
                        尾页
                    </a>
                </li>
            @endif
        </ul>
        <p class="page-to">至<input type="text">页</p>
    </div>
@endif
<!--
<div class="list-page">
    <ul class="pagination">
        <li>上一页</li>
        <li>1</li>
        <li>2</li>
        <li>3</li>
        <li>4</li>
        <li>5</li>
        <li>...</li>
        <li>下一页</li>
    </ul>
    <p class="page-to">至<input type="text">页</p>
</div>
-->