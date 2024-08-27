@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.trial_users')}}@endsection
@section('head')
@include('admin.inc.datatables-css')
<style>
    td {
        vertical-align: middle !important;
    }
</style>
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
                <hr>
                <div class="table-responsive ">
                    <table id=" datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>
                                    <div class="checkbox text-center checkbox-primary">
                                        <input id="checkbox--1" type="checkbox" value="-1" data-value="-1">
                                        <label for="checkbox--1">
                                        </label>
                                    </div>
                                </th>
                                <th>{{__('global.first_name')}}</th>
                                <th>{{__('global.last_name')}}</th>
                                <th>{{__('global.email')}}</th>
                                <th>{{__('global.image')}}</th>
                                <th>{{__('global.created_at')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trial_users->items() as $user)
                            <tr>
                                <td>

                                    <div class="checkbox text-center checkbox-primary">
                                        <input id="checkbox-{{$user->id}}" type="checkbox" value="{{$user->id}}" data-value="{{$user->id}}">
                                        <label for="checkbox-{{$user->id}}">
                                        </label>
                                    </div>

                                </td>
                                <td>{{$user->tryingRecords->first()->first_name}}</td>
                                <td>{{$user->tryingRecords->first()->last_name}}</td>
                                <td>{{$user->email}}</td>
                                <td> <img style="height: 150px;" class="img-responsive center-block d-block mx-auto" src="{{$user->image? storage_asset($user->image) : storage_asset('/defaults/default-user.png')}}" /></td>
                                <td>{{$user->created_at}}</td>
                                <td>
                                    <a href="{{route('web.getTryingUserProfile', ['user_id'=>$user->id])}}" class="btn btn-sm btn-outline-warning" title="{{__('admin.user_show')}}">
                                        <i class="fa fa-user"></i>
                                    </a>

                                    <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.delete_trial_users', ['user'=>$user->id])}}">
                                        @csrf
                                        @method('DELETE')
                                        <button href="#" class="btn btn-sm btn-danger" title="{{__('admin.users_delete')}}"><i class="mdi mdi-delete"></i></button>
                                    </form>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <div class="justify-content-center">
                    {!! $trial_users->links() !!}
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