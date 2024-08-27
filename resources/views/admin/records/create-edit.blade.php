@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.records')}}@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{$record->exists?__('admin.records_edit'):__('admin.records_add') }}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{$record->exists?route('admin.records.update', ['record'=>$record->id]):route('admin.records.store')}}">
                        @csrf
                        @if($record->exists)
                            @method('PUT')
                        @endif
                        @foreach($record->form_fields as $key => $field)
                            <x-admin.forms.form-group :field="$field" :name="$key" :item="$record"/>
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
@section('scripts')
    @include('admin.inc.record')
@endsection
