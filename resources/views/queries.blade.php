@extends('layouts.app')

@section('bodyclass', 'ad-post')

@section('content')
<div class="block-left {{ $version ?? 'b' }}-content-wrapper" style="width: 88.3%;">
    @if($version != 'd') 
        @include('elements.banners.top')
    @endif

    <!-- Post list -->
    <div class="ad-post-wrapper">

        <h1 class="page-title">{{ request()->route()->controller->header() }}</h1>

        <!-- Render result module -->
        <div class="create-ad-wrapper" >
            <table class="table table-striped" style="font-size: 14px; table-layout: fixed;">
                <col class="col1" style="width: 60%;">
                <thead>
                    <tr>
                        <td ><b>Запрос</b></td>
                        <td><b>Количество переходов</b></td>
                        <td><b>Количество записей</b></td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($queries as $q)
                        <tr >
                            <td style="word-wrap: break-word;"><a href="{{ $q->getLink() }}">{{ str_limit($q->query, 50) }}</a></td>
                            <td style="word-wrap: break-word;">{{ $q->follows }}</td>
                            <td style="word-wrap: break-word;">{{ $q->searchResults() ? $q->searchResults()->count : 0 }}</td>
                        </tr>
                    @empty
                        <tr></tr>
                    @endforelse

                </tbody>
            </table>
            {{ $queries->links('elements.pagination') }}
            
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