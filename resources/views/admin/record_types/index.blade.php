@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.record_types')}}@endsection
@section('head')
    @include('admin.inc.datatables-css')
@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.record_types_list')}}
                        @can('create', \App\Models\RecordType::class)
                            <a href="{{route('admin.record_types.create')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.add_new')}}</a>
                        @endcan
                        @can('delete', \App\Models\RecordType::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.record_types.batch_destroy')}}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="bulk_delete" value="[]">
                                <button href="#" class="float-right btn btn-danger" type="submit"><i class="mdi mdi-delete"></i> {{__('admin.batch_delete')}}</button>
                            </form>
                        @endcan
                    </h4>
                    <hr>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>
                                    <div class="checkbox text-center checkbox-primary">
                                        <input id="checkbox--1" type="checkbox" value="-1" data-value="-1">
                                        <label for="checkbox--1">
                                        </label>
                                    </div>
                                </th>
                                <th>{{__('global.name')}}</th>
                                <th>{{__('global.type_order')}}</th>
                                <th>{{__('global.account_type')}}</th>
                                <th>{{__('global.creator')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($record_types->items() as $record_type)
                                <tr>
                                    <td>
                                        @if(! $record_type->is_system)
                                            @can('delete', $record_type)
                                                <div class="checkbox text-center checkbox-primary">
                                                    <input id="checkbox-{{$record_type->id}}" type="checkbox" value="{{$record_type->id}}" data-value="{{$record_type->id}}">
                                                    <label for="checkbox-{{$record_type->id}}">
                                                    </label>
                                                </div>
                                            @endcan
                                        @else
                                            <div class="text-center text-danger"><p class="text-uppercase">{{__('admin.system')}}</p></div>
                                        @endif
                                    </td>
                                    <td>{{$record_type->trans('name')}}</td>
                                    <td>{{$record_type->type_order}}</td>
                                    <td>{{__('global.'.$record_type->account_type)}}</td>
                                    <td>@if($record_type->user){{$record_type->user->full_name}}@else {{__('global.admin')}}@endif</td>
                                    <td>
                                        @can('translate', $record_type)
                                            <a href="{{route('admin.record_types.translate', ['record_type'=>$record_type->id])}}" class="btn btn-sm btn-outline-warning"><i class="mdi mdi-translate"></i></a>
                                        @endcan
                                         @can('update', $record_type)
                                            <a href="{{route('admin.record_types.edit', ['record_type'=>$record_type->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $record_type)
                                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.record_types.destroy', ['record_type'=>$record_type->id])}}">
                                                @csrf
                                                @method('DELETE')
                                                <button href="#" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                                            </form>
                                        @endcan

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="justify-content-center">
                        {!! $record_types->links() !!}
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- End Row -->


@endsection
@section('scripts')
    @include('admin.inc.bulkdelete')
    @include('admin.inc.smarttoggles')
    @include('admin.inc.datatable')
@endsection
