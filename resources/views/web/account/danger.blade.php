@extends('web.layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/web/about.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/side-nav.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/card.css?v=1')}}" />

@endsection
@section('content')
    <section class="about_section padding_top">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3 col-xl-4">
                    @include('web.partials.account-navbar')
                </div>
                <div class="col-12 col-md-9 col-xl-8">
                    <div class="contact_section_content">
                        @if(session()->has('success'))
                            <label class="w-100 alert alert-success alert-dismissible fade show" role="alert">
                                {{session()->get('success')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </label>
                        @endif
                        <h4 class="mb-4">{{__("global.danger_zone")}}</h4>
                        <div class="card mb-3">
                            <div class="card-header font-weight-bold">
                                {{__("global.delete_my_account")}}
                            </div>
                            <div class="card-body">
                                <div class="card-text">
                                    <p>{{__('global.delete_account_text')}}</p>
                                    {{-- <p class="text-warning mt-2 "><i class="fa fa-exclamation-triangle"></i> <small>This action is permanent
                                            if you want to hide your profile from public access you can do that </small>
                                        <a href="{{route('account.status')}}" class="d-inline-block btn btn-link text-light p-0" style="min-width: unset">here</a></p> --}}
                                    <div class="w-100 d-flex mt-4">
                                        <form class="ml-auto" method="POST" action="{{route('account.deleteAccount')}}" id="danger-form">
                                            @csrf
                                            <button type="submit" class="btn btn-danger "><i class="fa fa-times"></i> {{__('global.delete_my_account')}}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
    form = document.getElementById("danger-form")
        form.onsubmit = function (){
        event.preventDefault();
        swal(
            {
                title:"{{__('admin.are_you_sure')}}",
                text:"{{__('admin.action_undone')}}",
                type:"error",
                showCancelButton:!0,
                confirmButtonClass:"primary-btn",
                confirmButtonText:"{{__('admin.do_it')}}",
                cancelButtonText: "{{__('admin.cancel')}}",
                closeOnConfirm: false
            }, function (confirm) {
                if (confirm) {
                    form.submit();
                }
            });
    }
    </script>
    @endsection
