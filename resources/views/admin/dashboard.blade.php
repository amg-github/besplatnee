@extends('layouts.app')

@section('bodyclass', 'cpanel')
@section('bottombanners')
@endsection
@section('topbanners')
@endsection

@section('content')
<style>
    .version-a.cpanel .a-content-wrapper, .version-b.cpanel .b-content-wrapper {
        margin: 0 0 0 20px;
    }
</style>

<div class="left-block {{ $version ?? 'c' }}-content-wrapper">

    <!-- Post list -->
    <div class="posts-wrapper clearfix">

        <h1 class="page-title">{{ request()->route()->controller->header() }}</h1>

        <!-- Render result module -->
        	@include('admin.menu')
        <div class="result-wrapper">

        	@yield('admincontent')
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