@extends('layouts.app')

@section('content')
<div class="block-left {{ $version ?? 'b' }}-content-wrapper">
    @if($version != 'd') 
        @include('elements.banners.top')
    @endif

    <!-- Post list -->
    <div class="posts-wrapper clearfix">

        <h1 class="page-title">{{ request()->route()->controller->header() }}</h1>

        <!-- Render result module -->
        <div class="result-wrapper">
            <div class="post-list-wrapper clearfix">

                @forelse($adverts as $advert) 
                    @include('advert.previews.text')
                @empty
                    <p><center>@lang('adverts.empty')</center></p>

                    @foreach($empty as $advert) 
                        @include('advert.previews.text')
                    @endforeach
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
    
@section('leftsidebar')
    
@endsection

@section('rightsidebar')
    <div class="block-left right-sidebar-banner-wrapper">
        @include('elements.banners.right', [
            'category' => null,
            'block_number' => 0,
        ])
        @include('elements.banners.right', [
            'category' => null,
            'block_number' => 1,
        ])
    </div>
@endsection