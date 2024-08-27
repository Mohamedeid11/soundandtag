@extends('admin.partials.master')
@section('title'){{__('global.translate')}}@endsection
@section('header_title'){{__('global.translate')}}@endsection
@section('head')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/ckeditor/styles.css')}}">
    <link href="//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{__('admin.translatex', ['thing'=>__('global.role')])}}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{route('admin.'.$obj->getTable().'.translation', $obj->id)}}">
                        @csrf
                            @method('PUT')
                        @include('admin.inc.translation-form', ['obj'=>$obj])
                        <div class="form-group row justify-content-center">
                            <input type="submit" class="btn btn-primary" value="{{__('admin.submit')}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection
@section('scripts')
    @include('admin.inc.translation-form-scripts')
    @endsection
