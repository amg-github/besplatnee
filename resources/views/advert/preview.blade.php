@extends('layouts.app')

@section('bodyclass', '')

@section('content')
<div class="version-a">
    <div class="block-left a-content-wrapper">

        <div class="posts-wrapper clearfix">

            <!-- Page title  -->

            <h1 class="page-title block-left">@lang('adverts.preview.title')</h1>
            <div class="clearfix"></div>

            <!-- End page title -->
            <h1 class="page-title block-left">@lang('adverts.preview.a')</h1>
            <div class="result-wrapper">
                <div class="post-list-wrapper clearfix">
                    @include('advert.previews.adaptive')
                </div>
            </div>
            <div class="post-list-wrapper clearfix"></div>

        </div>

    </div>
</div>
            <div class="clearfix"></div>
<div class="version-b">
    <div class="block-left b-content-wrapper">

        <div class="posts-wrapper clearfix">

            <h1 class="page-title block-left">@lang('adverts.preview.b')</h1>
            <div class="result-wrapper">
                <div class="post-list-wrapper clearfix">
                    @include('advert.previews.text')
                </div>
            </div>
            <div class="post-list-wrapper clearfix"></div>

        </div>

    </div>
</div>
            <div class="clearfix"></div>

<div class="version-c">
    <div class="c-content-wrapper">

        <div class="posts-wrapper clearfix">
        
            <h1 class="page-title block-left">@lang('adverts.preview.c')</h1>
            <div class="clearfix"></div>
            <div class="result-wrapper">
                <div class="post-list-wrapper clearfix">
                    <div class="post-list-head-title text-center clearfix">
                        <div class="block-left">@lang('adverts.actions')</div>
                        <div class="block-left">@lang('adverts.adverts')</div>
                        <div class="block-left">@lang('adverts.comments')<br><span>(@lang('adverts.comments.description'))</span>
                        </div>
                    </div>
                    @include('advert.previews.table')
                </div>
            </div>
            <div class="post-list-wrapper clearfix"></div>

        </div>

    </div>
</div>
            <div class="clearfix"></div>

<div class="version-e">
    <div class="e-content-wrapper">

        <div class="posts-wrapper clearfix">
        
            <h1 class="page-title block-left">@lang('adverts.preview.d')</h1>
            <div class="clearfix"></div>
            <div class="result-wrapper">
                <div class="post-list-wrapper clearfix">
                    @include('advert.previews.gallery')
                </div>
            </div>
            <div class="post-list-wrapper clearfix"></div>

        </div>

    </div>
</div>
            <div class="clearfix"></div>

<div class="ad-post">
<div class="block-left b-content-wrapper" style="    width: 100%;">
    <div class="ad-post-wrapper">
        <div class="ad-post-content">
            <div class="add-post-params clearfix">

                <div class="buttons block-right" style="margin-left: 12px;">

                    <a class="block-left add-create-next" href="{{ route('advert-success', ['id' => $advert->id]) }}"><span>@lang('site.phrases.complete')</span><i class="fa fa-angle-right" aria-hidden="true"></i></a>

                </div>

                <div class="buttons block-right">

                    <a class="block-left add-create-next" href="{{ route('advert-edit', ['id' => $advert->id]) }}"><i class="fa fa-angle-left" aria-hidden="true"></i>&nbsp;<span>@lang('adverts.edit')</span></a>

                </div>

            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('leftsidebar')
    
@endsection

@section('rightsidebar')
    
@endsection