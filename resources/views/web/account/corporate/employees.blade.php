@extends('web.layouts.master')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
<link rel="stylesheet" href="{{asset('css/web/about.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/side-nav.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/card.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/employees.css?v=1')}}" />
@endsection
@section('content')
<section class="about_section padding_top">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="contact_section_content">
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

                    <h4 class="mb-2">{{__('global.employees')}} ({{$user->employees()->count()}}/{{$user->items}})</h4>
                    @if ($user->items > $user->employees()->count())
                    <form class="form-inline justify-content-center" method="post" action="{{route('account.addEmployee')}}">
                        @csrf
                        <label class="text-light mr-4" for="inlineFormInputName2">{{__('global.add_new')}}</label>
                        <input type="email" name="email" class="form-control mb-2 mr-sm-2 @if($errors->has('email')) is-invalid @endif" id="inlineFormInputName2" placeholder="{{__('global.email')}}" required value="{{inp_value(null, 'email')}}">
                        <input type="text" name="name" class="form-control mb-2 mr-sm-2 @if($errors->has('name')) is-invalid @endif" id="inlineFormInputName2" placeholder="{{__('global.name')}}" required value="{{inp_value(null, 'name')}}">
                        <select type="text" name="category_id" id="category-input" class="form-control mb-2 mr-sm-2 cu_input @if(! $user->category_id) required_error @endif check_error @if($errors->has('category_id')) is-invalid @endif @if(! $user->category_id) @endif" required>
                            <option value="">Select</option>
                            @foreach($categories as $category)
                            <option value="{{$category->id}}" {{select_value($user, 'category_id', $category->id)}}>{{$category->name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('category_id'))
                        <div class="invalid-feedback">
                            @foreach($errors->get('category_id') as $error)
                            <span>{{$error}}</span>
                            @endforeach
                        </div>
                        @endif
                        <button type="submit" class="btn btn-primary mb-2">{{__('global.submit')}}</button>
                        @if($errors->has('email'))
                        <div class="invalid-feedback text-center">
                            @foreach($errors->get('email') as $error)
                            <span>{{$error}}</span>
                            @endforeach
                        </div>
                        @endif

                        @if($errors->has('name'))
                        <div class="invalid-feedback text-center">
                            @foreach($errors->get('name') as $error)
                            <span>{{$error}}</span>
                            @endforeach
                        </div>
                        @endif
                    </form>
                    <span class="divider">{{__('global.or')}}</span>
                    <form class="form-inline justify-content-center" method="post" action="{{route('account.addEmployeeExcel')}}" enctype='multipart/form-data'>
                        @csrf
                        <input type="file" name="sheet" class="form-control mb-2 mr-sm-2 @if($errors->has('email')) is-invalid @endif" id="upload-sheet" required hidden>
                        <label for="upload-sheet" class="btn btn-success mb-2">
                            <i class="fa fa-upload mr-1"></i>
                            {{__('global.upload_sheet')}}
                            <span class="tooltip-text">{{__('global.upload_sheet_instructions')}}</span>
                        </label>
                        <p class="file-uploaded mx-3"></p>
                        <button type="submit" class="btn btn-primary mb-2">{{__('global.submit')}}</button>
                    </form>
                    @endif
                    @if($user->employees()->count() > 0)
                    <div class="overflow-auto employees-table mt-2">
                        <table class="table text-light table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('global.email')}}</th>
                                    <th>{{__('global.name')}}</th>
                                    <th>{{__('global.category')}}</th>
                                    <th>{{__('global.status')}}</th>
                                    <th>{{__('global.profile_status')}}</th>
                                    <th>{{__('global.invited_at')}}</th>
                                    <th>{{__('global.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invitations as $employee)
                                <tr @if($employee->employee_id == 0)
                                    style="background-color:#47090d"
                                    @endif
                                    >
                                    <td></td>
                                    <td>
                                        {{$employee->email}}
                                    </td>
                                    <td>
                                        {{$employee->name}}
                                    </td>
                                    <td>{{$employee->category_id? $employee->category->name : ''}}</td>
                                    <td>{{$employee->status}}</td>
                                    <td>{{$employee->public ? __('global.public') : ($employee->employee_id == 0? "-":__('global.not_public'))}}</td>

                                    <td>
                                        {{$employee->created_at}}
                                    </td>
                                    <td class="data-cell-actions">
                                        @if($employee->employee_id == 0 )
                                        <form method="POST" action="{{route('account.resendEmployeeInvitation', ['employee'=>$employee->id])}}" class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="resend invitation email"><i class="fa fa-envelope"></i> {{__('global.resend')}}</button>
                                        </form>
                                        @endif


                                        <form class="d-inline-block danger-form" method="post" onsubmit="submitForm()" action="{{route('account.deleteEmployee', ['employee'=>$employee->id])}}">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> {{__('global.delete')}}</button>
                                        </form>

                                        @if($employee->user && $employee->user->is_public)
                                        <a href="{{route('web.profile',  $employee->username)}}" class="btn btn-primary"><i class="fa fa-user"></i> {{__('global.profile')}}</a>
                                        @endif

                                        @if($employee->employee_id != 0 && $employee->public == 0)
                                        <form method="POST" action="{{route('account.remindEmployeeToGoPublic', ['employee'=>$employee->id])}}" class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="Remind employee to go public"><i class="fa fa-envelope"></i> {{__('global.remind')}}</button>
                                        </form>
                                        @endif


                                    </td>
                                </tr>
                                @endforeach

                                @foreach($employees as $employee)
                                <tr @if($employee->employee_id == 0)
                                    style="background-color:#47090d"
                                    @endif
                                    >
                                    <td>
                                        {{$loop->index+1}}
                                    </td>
                                    <td>
                                        {{$employee->email}}
                                    </td>
                                    <td>
                                        {{$employee->user->name}}
                                    </td>
                                    <td>{{$employee->user->category_id? $employee->user->category->name : ''}}</td>
                                    <td>{{$employee->status}}</td>
                                    <td>{{$employee->public ? __('global.public') : ($employee->employee_id == 0? "-":__('global.not_public'))}}</td>

                                    <td>
                                        {{$employee->created_at}}
                                    </td>
                                    <td class="data-cell-actions">
                                        @if($employee->employee_id == 0 )
                                        <form method="POST" action="{{route('account.resendEmployeeInvitation', ['employee'=>$employee->id])}}" class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="resend invitation email"><i class="fa fa-envelope"></i> {{__('global.resend')}}</button>
                                        </form>
                                        @endif


                                        <form class="d-inline-block danger-form" method="post" onsubmit="submitForm()" action="{{route('account.deleteEmployee', ['employee'=>$employee->id])}}">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> {{__('global.delete')}}</button>
                                        </form>

                                        @if($employee->user && $employee->user->is_public)
                                        <a href="{{route('web.profile',  $employee->username)}}" class="btn btn-primary"><i class="fa fa-user"></i> {{__('global.profile')}}</a>
                                        @endif

                                        @if($employee->employee_id != 0 && $employee->public == 0)
                                        <form method="POST" action="{{route('account.remindEmployeeToGoPublic', ['employee'=>$employee->id])}}" class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="Remind employee to go public"><i class="fa fa-envelope"></i> {{__('global.remind')}}</button>
                                        </form>
                                        @endif


                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-center">
                        <a class="btn primary-btn" href="{{route('account.employees_public_arrange')}}">{{__('global.arrange_employees')}}</a>
                    </div>
                    @else
                    <p class="my-5 w-100 text-center">{{__('global.no_employees_added')}} ({{$user->employees()->count()}}/{{$user->items}}) </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
@include('web.partials.axiosinit')
<script>
    function submitForm() {
        event.preventDefault();
        event.stopImmediatePropagation();
        const form = event.target;
        swal({
            title: "{{__('admin.are_you_sure')}}",
            text: "{{__('admin.action_undone')}}",
            type: "error",
            showCancelButton: !0,
            confirmButtonClass: "primary-btn",
            confirmButtonText: "{{__('admin.do_it')}}",
            cancelButtonText: "{{__('admin.cancel')}}",
            closeOnConfirm: false
        }, function(confirm) {
            if (confirm) {
                form.submit();
            }
        });
    }

    // Upload sheet
    const uploadSheetInp = document.getElementById("upload-sheet");
    uploadSheetInp.addEventListener("change", (e) => {
        document.querySelector(".file-uploaded").innerHTML = e.target.files[0].name
    })
</script>

@endsection
