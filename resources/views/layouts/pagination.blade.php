<!-- Start pagination -->

@if($pagecount > 0)
<div class="pagination-wrapper">

    <div class="pagination">

        <a href="{{ url()->current() }}?page=1" class="arrow-left page-first"><i class="fa fa-angle-double-left"

                                                     aria-hidden="true"></i></a>

        <a href="{{ url()->current() }}?page={{ $pagecurrent > 1 ? $pagecurrent - 1 : 1 }}" class="arrow-left page-prev"><i class="fa fa-angle-left"

                                                    aria-hidden="true"></i></a>

        @for($i = 1; $i <= $pagecount; $i++)
        <a href="{{ url()->current() }}?page={{ $i }}">{{ $i }}</a>
        @endfor

        <a href="{{ url()->current() }}?page={{ $pagecurrent < $pagecount ? $pagecurrent + 1 : $pagecount }}" class="arrow-right page-next"><i class="fa fa-angle-right"

                                                     aria-hidden="true"></i></a>

        <a href="{{ url()->current() }}?page={{ $pagecount }}" class="arrow-right page-last"><i

                class="fa fa-angle-double-right" aria-hidden="true"></i></a>

    </div>

</div>
@endif

<!-- End pagination  -->