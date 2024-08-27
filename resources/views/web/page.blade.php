@extends("web.layouts.master")
@section('styles')
<link rel="stylesheet" href="{{asset('css/web/about.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/side-nav.css?v=1')}}" />
<style>
    p,
    ul {
        color: #ffff !important;
    }
</style>
@endsection
@section('content')
<section class="about_section padding_top">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-3 col-xl-4">
                @if (request()->route()->getName() == 'web.get_about_us' || request()->route()->getName() == 'web.get_about' )
                @include('web.partials.about-us-navbar')
                @endif
            </div>
            <div class="{{ (request()->route()->getName() == 'web.get_terms') ? 'col-md-11' : 'col-md-8'}} col-12 m-auto">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</section>
@endsection
