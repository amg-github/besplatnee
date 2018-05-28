<div class="dropdown-menu" aria-labelledby="dLabel">
    <div class="more-links-wrapper clearfix">
        <div class="header-categories-all js-header-more-content">
            <div class="header-categories-all-all"><a href="{{ route('home') }}">Все категории</a></div>
            <div class="header-categories-all-column-wrapper clearfix">
                <div class="header-categories-all-column">
                    @php($menucounter = 0)
                    @foreach($headings as $heading)
                        @php($menucounter += 2 + \Besplatnee::headings()->getChildrensCount($heading))
                        @if($menucounter > 44)
                            @php($menucounter = 2 + \Besplatnee::headings()->getChildrensCount($heading))
                </div>
                <div class="header-categories-all-column">
                        @endif 
                        <ul class="header-categories-all-list">
                            @include('elements.header.categories.folder', [
                                'heading' => $heading,
                            ])
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>