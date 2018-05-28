@extends('layouts.app')

@section('bodyclass', 'ad-post')

@section('content')
<div class="block-left b-content-wrapper" style="width: 62%;margin-left: 18%;margin-right: 5.2%;">


    <div class="ad-post-wrapper">

        <!-- Page title  -->

        <h1 class="page-title block-left">{{ request()->route()->controller->header() }}</h1>

        <!-- End page title -->



        <div class="clearfix"></div>

        <div class="create-ad-wrapper">

            @yield('pagecontent')

        </div>



    </div>
    

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
    </div>
@endsection