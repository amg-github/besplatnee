<div class="version-sites">
    <span>Вид просмотра</span>
    <a href="{{ url()->current() }}?v=a&page={{ $pagecurrent }}" class="active"><i class="fa fa-mobile"
                        aria-hidden="true"></i><span>Адаптивный</span></a>
    <a href="{{ url()->current() }}?v=b&page={{ $pagecurrent }}"><i class="fa fa-newspaper-o" aria-hidden="true"></i><span>Текстовый</span></a>
    <a href="{{ url()->current() }}?v=c&page={{ $pagecurrent }}"><i class="fa fa-table" aria-hidden="true"></i><span>Таблица</span></a>
    <a href="{{ url()->current() }}?v=d&page={{ $pagecurrent }}"><i class="fa fa-map-o"
                        aria-hidden="true"></i><span>Карта</span></a>
    <a href="{{ url()->current() }}?v=e&page={{ $pagecurrent }}"><i class="fa fa-file-image-o"
                   aria-hidden="true"></i><span>Картинки</span></a>
</div>