@extends('web.layouts.master')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
<link rel="stylesheet" href="{{asset('css/web/about.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/side-nav.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/card.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/employees.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/profileCard.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/profiles.css?v=1')}}" />
@endsection
@section('content')
<section class="about_section padding_top">
    <div class="container">
        @if(session()->has('success'))
        <label class="w-100 alert alert-success alert-dismissible fade show" role="alert">
            {{session()->get('success')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </label>
        @elseif(session()->has('error'))
        <label class="w-100 alert alert-danger alert-dismissible fade show" role="alert">
            {{session()->get('error')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </label>
        @endif

        <div class="mt-5 row">
            @if($employees->count() > 0 || request()->search)
            <div class="col-md-3 col-12">
                <nav class="nav flex-column side-nav">
                    @foreach($categories as $category)
                        <a class="btn btn-link text-md-left choose-category" data-id="{{$category->id}}">{{$category->name}}</a>
                    @endforeach
                </nav>
            </div>

            <div class="employees-data col" hidden>
                <div class="row">
                    <div class="col-lg-10 m-auto">
                        <form id="search-form" class="search_form">
                            <div class="sidebar_part">
                                <div class="single_sidebar d-flex">
                                    <div class="position-relative flex-fill h-100">
                                        <input type="text" placeholder="{{__('global.search')}}..." id="search_profiles" value="{{request()->search}}" name="search">
                                        <button type="reset" class="d-inline-block border-0 p-0 position-absolute" onclick="resetSearchForm()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <button type="submit" class="secondary-btn btn ml-2" style="border-radius: 6px">{{__('global.search')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row members mt-4">
                    @if($employees->count() > 0)
                    @foreach($employees as $profile)
                    <div class="col-lg-2 col-4 employee-card" data-category="{{$profile->category? $profile->category->id :0}}">
                        @include('web.partials.profile-card')
                    </div>
                    @endforeach
                    @else
                    <p class="text-center w-100">{{__('global.no_employees_added')}}</p>
                    @endif
                </div>
                <div class="justify-content-center">
                    {{$employees->render()}}
                </div>
            </div>
            @else
            <p class="text-center w-100">{{__('global.no_employees_added')}}</p>
            @endif
        </div>
    </div>
</section>
@endsection
@section('scripts')
@include('web.partials.axiosinit')
<script>
    // Select a category
    const selectCategory = (elm) => {
        const categoryId = elm.dataset.id;

        document.querySelectorAll(".choose-category").forEach(btn => btn.classList.remove("active"))
        elm.classList.add("active");

        document.querySelector(".employees-data").removeAttribute("hidden");
        document.querySelectorAll(".employee-card").forEach(box => {
            if(box.dataset.category === categoryId)
                box.removeAttribute("hidden");
            else
                box.setAttribute("hidden", "true");
        })
    }

    document.querySelectorAll(".choose-category").forEach((btn, i) => {
        if(!i) selectCategory(btn);

        btn.addEventListener("click", (e) => selectCategory(e.currentTarget));
    });

    // Reset search form 
    const resetSearchForm = () => {
        document.querySelector("#search_profiles").value = "";
        document.querySelector("#search-form").submit();
    }
</script>

@endsection
