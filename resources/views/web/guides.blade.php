@extends("web.layouts.master")
@section('styles')
    <link rel="stylesheet" href="{{asset('css/web/about.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/side-nav.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/card.css?v=1')}}" />
@endsection
@section('content')
    <section class="about_section padding_top">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3 col-xl-4">
                    @include('web.partials.other-pages-navbar')
                </div>
                <div class="col-12 col-md-9 col-xl-8">
                    <h1 style="color:var(--secondary-color); font-weight: 400">{{__("global.guide")}}</h1>
                    <div class="accordion" id="faqAccordion">
                        @foreach($guides as $guide)
                            <div class="card">
                                <div class="card-header p-0">
                                    <button class="btn text-left collapsed px-4 py-3 w-100" type="button" data-toggle="collapse" data-target="#collapse-{{$guide->id}}" aria-expanded="false" aria-controls="collapseOne">
                                        {!!$guide->trans('name')!!}
                                    </button>
                                </div>

                                <div id="collapse-{{$guide->id}}" class="collapse accordion-card" data-parent="#faqAccordion">
                                    <div class="card-body border-bottom-0 rounded-0 text-white">
                                        {!! $guide->explanation !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
