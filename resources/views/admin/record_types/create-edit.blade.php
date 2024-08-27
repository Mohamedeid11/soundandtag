@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.record_types')}}@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{$record_type->exists?__('admin.record_types_edit'):__('admin.record_types_add') }}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{$record_type->exists?route('admin.record_types.update', ['record_type'=>$record_type->id]):route('admin.record_types.store')}}">
                        @csrf
                        @if($record_type->exists)
                            @method('PUT')
                        @endif
                        @foreach($record_type->form_fields as $key => $field)
                            <x-admin.forms.form-group :field="$field" :name="$key" :item="$record_type"/>
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
