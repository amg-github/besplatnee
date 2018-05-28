@extends('layouts.app')
@section('bodyclass', 'cpanel')

@section('content')
<div class="{{ $version ?? 'c' }}-content-wrapper">

    <!-- Post list -->
    <div class="posts-wrapper clearfix">

        <h1 class="page-title">{{ request()->route()->controller->header() }}</h1>

        <!-- Render result module -->
        	@include('office.menu')
        <div class="result-wrapper">

        	@yield('officecontent')
        </div>

        <!-- End render result module -->

    </div>
    <!-- End post list -->
</div>
@endsection

@section('leftsidebar')
	
@endsection

@section('rightsidebar')
	
@endsection