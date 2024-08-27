@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.profile')}}@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('success'))
                        <label class="alert alert-success w-100 alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {{Session::get('success')}}
                        </label>
                    @endif
                    @if(Session::has('error'))
                        <label class="alert alert-danger w-100 alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {{Session::get('error')}}
                        </label>
                    @endif
                    <h4 class="m-b-30 m-t-0">{{__('global.edit_profile')}}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{route('admin.post_profile')}}">
                        @csrf
                        @method('PUT')
                        @foreach($admin->form_fields->only(['name', 'image', 'password']) as $key => $field)
                            <x-admin.forms.form-group :field="$field" :name="$key" :item="$admin"/>
                        @endforeach
                        <div class="form-group row justify-content-center">
                            <input type="submit" class="btn btn-primary" value="{{__('admin.submit')}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
