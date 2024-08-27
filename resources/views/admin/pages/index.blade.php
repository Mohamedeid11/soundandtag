@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.pages')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.pages_list')}}
                        @can('create', \App\Models\Page::class)
                            <a href="{{route('admin.pages.create')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.add_new')}}</a>
                        @endcan
                        @can('delete', \App\Models\Page::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.pages.batch_destroy')}}">
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
                                <th>{{__('global.content_length')}}</th>
                                <th>{{__('global.url')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pages->items() as $page)
                                <tr>
                                    <td>
                                        @if(! $page->is_system)
                                            @can('delete', $page)
                                                <div class="checkbox text-center checkbox-primary">
                                                    <input id="checkbox-{{$page->id}}" type="checkbox" value="{{$page->id}}" data-value="{{$page->id}}">
                                                    <label for="checkbox-{{$page->id}}">
                                                    </label>
                                                </div>
                                            @endcan
                                        @else
                                            <div class="text-center text-danger"><p class="text-uppercase">{{__('admin.system')}}</p></div>
                                        @endif
                                    </td>
                                    <td>{{$page->name}}</td>
                                    <td>{{Str::length($page->content)}}</td>
                                    <td><a href="{{ route('web.page', ['name'=>$page->name])}}">{{__('admin.go_to_page')}}</a></td>
                                    <td>
                                            @can('translate', $page)
                                                <a href="{{route('admin.pages.translate', ['page'=>$page->id])}}" class="btn btn-sm btn-outline-warning"><i class="mdi mdi-translate"></i></a>
                                            @endcan
                                                                                        @can('update', $page)
                                                <a href="{{route('admin.pages.edit', ['page'=>$page->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                            @endcan
                                                @if(! $page->is_system)
                                                @can('delete', $page)
                                                <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.pages.destroy', ['page'=>$page->id])}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button href="#" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                                                </form>
                                            @endcan
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="justify-content-center">
                        {!! $pages->links() !!}
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
