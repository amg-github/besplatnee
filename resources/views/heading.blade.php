@extends('layouts.app')

@section('content')
<div class="{{ $version == 'a' || $version == 'b' ? 'block-left' : '' }} {{ $version ?? 'b' }}-content-wrapper">

    @include('elements.versions', ['version' => $version ?? 'a'])

    

        <div class="row">
            @foreach(Besplatnee::headings()->getChildrenWithProperties($category, $parent_filter_id, $parent_filter_value) as $alias)
                <div class="col-xs-4">
                    <a href="{{ $alias['url'] }}">@lang($alias['caption'])</a>
                </div>
            @endforeach
        </div>

    <!-- Post list -->
    <div class="posts-wrapper clearfix">


        <!-- Render result module -->
        <div class="result-wrapper">
            <div class="post-list-wrapper clearfix">
                @if($version == 'c') 
                    <div class="post-list-head-title text-center clearfix">
                        <div class="block-left">@lang('adverts.actions')</div>
                        @if(Auth::check())
                            <div class="block-left">@lang('adverts.adverts')</div>
                            <div class="block-left">@lang('adverts.comments')<br><span>(@lang('adverts.comments.description'))</span></div>
                        @else
                            <div class="block-left c-advert-info-full">@lang('adverts.adverts')</div>
                        @endif
                    </div>
                @endif

                @forelse($adverts->chunk(5) as $chunkAdverts) 
                    @foreach($chunkAdverts as $advert)
                        @if($version == 'a')
                            @include('advert.previews.adaptive')
                        @elseif($version == 'b')
                            @include('advert.previews.text')
                        @elseif($version == 'c')
                            @include('advert.previews.table')
                        @elseif($version == 'd')
                            
                        @elseif($version == 'e')
                            @include('advert.previews.gallery')
                        @endif
                    @endforeach

                    @if($version == 'a' || $version == 'b') 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="block-left right-sidebar-banner-wrapper">
                            @include('elements.banners.right', [
                                'category' => null,
                                'block_number' => $loop->index,
                            ])
                        </div>
                                    
                        <div id="banners">
                            <div class="banners-top clearfix">
                                <div class="fw-container">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        @include('elements.banners.advert', [
                                            'category' => null,
                                            'block_number' => $loop->index,
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="{{ $version == 'a' || $version == 'b' ? 'block-left' : '' }} {{ $version ?? 'b' }}-content-wrapper">
                            <div class="posts-wrapper clearfix">
                                <div class="result-wrapper">
                                    <div class="post-list-wrapper clearfix">
                    @endif
                @empty
                    <p><center>@lang('adverts.empty')</center></p>
                @endforelse
            </div>

            <div class="post-list-wrapper clearfix"></div>

            {{ $adverts->links('elements.pagination') }}

        </div>

        <!-- End render result module -->

    </div>
    <!-- End post list -->
</div>
@endsection