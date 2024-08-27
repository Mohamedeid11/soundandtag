@extends('web.layouts.master')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
<link rel="stylesheet" href="{{asset('css/web/about.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/side-nav.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/card.css?v=1')}}" />
<style>
    .modal-content {
        background: var(--background);
    }

    .modal-title,
    .close {
        color: var(--light-color);
    }

    .required_error {
        border: 3px solid red !important;
    }

    .cropper-crop-box,
    .cropper-view-box,
    .profile-pic-image {
        border-radius: 50%;
    }
</style>
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
                    <h4 class="mb-1">{{__('global.edit_details')}}</h4>
                    <form class="contact_form check_validity mt-3" action="{{route('account.update')}}" method="post" id="contactForm" enctype="multipart/form-data" onsubmit="checkVideoSize(event)">
                        @csrf
                        @method('PUT')

                        @if($user->account_type == 'corporate')
                        @include('web.partials.account-corporate')
                        @elseif ($user->account_type == 'personal')
                        @include('web.partials.account-personal')
                        @elseif ($user->account_type == 'employee')
                        @include('web.partials.account-employee')
                        @endif

                        <div class="single_contact_form form-group">
                            <label for="image-input">{{__('global.image')}}</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="revertToDefaultImage" name="revertImage">
                                <label class="custom-control-label text-warning" for="revertToDefaultImage"><strong>{{__('global.revert_default_img')}}</strong></label>
                            </div>
                            <div id="crop-preview" class="crop-preview preview col-3 p-0 m-auto" data-target="#profile-pic-chooser" onclick="$('#profile-pic-chooser').modal('show')">
                                <div class="profile-preview-div preview-div">
                                    <a href="#" data-toggle="modal" data-target="#profile-pic-chooser">
                                        <img class="profile-pic-image " height="100" width="100" src="{{storage_asset($user->image)}}" alt="user profile image">
                                    </a>
                                </div>
                            </div>
                            <label for="image-input" id="image-input-label" class="cu_input mt-2 d-flex align-items-center px-3 py-2" style="color: #7d7d7d">
                                <button class="border border-dark rounded-sm mr-2 bg-light" style="pointer-events: none">{{__('global.choose_image')}}</button>
                                <span>{{__('admin.placeholder_text', ['name'=>__('global.image')])}}</span>
                            </label>
                            <input type="file" name="image" id="image-input" class="form-control cu_input mt-2 @if($errors->has('image')) is-invalid @endif" hidden accept="image/*">
                            <input type="hidden" name="image_cropping">
                            @if($errors->has('image'))
                            <div class="invalid-feedback">
                                @foreach($errors->get('image') as $error)
                                <span>{{$error}}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="single_contact_form form-group">
                            <label for="video-input">
                                @if($user->account_type == 'corporate')
                                    {{__('global.upload_promotional_video')}}
                                @elseif ($user->account_type == 'personal')
                                    {{__('global.upload_identity_video')}}
                                @elseif ($user->account_type == 'employee')
                                    {{__('global.upload_professional_video')}}
                                @endif
                            </label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="delete_video"
                                    name="delete_video">
                                <label class="custom-control-label text-warning"
                                    for="delete_video"><strong>{{ __('global.delete_my_video') }}</strong></label>
                            </div>
                            <label for="video-input" id="video-input-label" class="cu_input mt-2 d-flex align-items-center px-3 py-2" style="color: #7d7d7d">
                                <button class="border border-dark rounded-sm mr-2 bg-light" style="pointer-events: none">{{__('global.choose_video')}}</button>
                                <span>{{__('admin.placeholder_text', ['name'=>__('global.video')])}}</span>
                            </label>
                            <input type="file" name="video" id="video-input"
                                class="form-control cu_input mt-2 @if($errors->has('video')) is-invalid @endif" hidden
                                accept="video/*">
                            <p style="font-size: 14px;color: var(--light-color);">
                                {{ __('global.video_size_limit') }}</p>
                            <div class="feedback"></div>
                            @if($errors->has('video'))
                            <div class="invalid-feedback">
                                @foreach($errors->get('video') as $error)
                                <span>{{$error}}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="single_contact_form d-flex">
                            <button type="submit" class="btn primary-btn text-uppercase ml-auto"> {{__('global.confirm')}}</button>
                        </div>
                    </form>
                    <div id="password-change">
                        <hr class="bg-light">
                        @if(session()->has('password_changed'))
                        <label class="w-100 alert alert-success alert-dismissible fade show" role="alert">
                            {{session()->get('password_changed')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </label>
                        @endif
                        <h4 class="mb-1">{{__('global.change_password')}}</h4>
                        <form class="contact_form check_validity mt-3" action="{{route('account.password.change')}}" method="post">
                            @csrf
                            @method('put')

                            <div class="single_contact_form form-group">
                                <label for="password-input">{{__('global.current_password')}} <sup class="required">*</sup></label>
                                <div class="input-group @if($errors->has('current_password')) is-invalid @endif">
                                    <input type="password" name="current_password" id="password-input" class="form-control @if($errors->has('current_password')) is-invalid @endif" placeholder="{{__('admin.placeholder_text',['name'=> __('global.current_password')])}}" required>
                                    <div class="input-group-append">
                                        <button type="button" class="input-group-text show-password"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                @if($errors->has('current_password'))
                                <div class="invalid-feedback">
                                    @foreach($errors->get('current_password') as $error)
                                    <span>{{$error}}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="single_contact_form form-group">
                                <label for="password-confirm-input">{{__('global.new_password')}} <sup class="required">*</sup></label>
                                <div class="input-group @if($errors->has('password')) is-invalid @endif">
                                    <input type="password" name="password" id="password-confirm-input" class="form-control @if($errors->has('password')) is-invalid @endif" placeholder="{{__('admin.placeholder_text',['name'=> __('global.new_password')])}}" required>
                                    <div class="input-group-append">
                                        <button type="button" class="input-group-text show-password"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    @foreach($errors->get('password') as $error)
                                    <span>{{$error}}</span><br>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="single_contact_form form-group">
                                <label for="confirm-password-input">{{__('global.new_password_confirmation')}} <sup class="required">*</sup></label>
                                <div class="input-group @if($errors->has('password_confirmation')) is-invalid @endif">
                                    <input type="password" name="password_confirmation" id="confirm-password-input" class="form-control @if($errors->has('password_confirmation')) is-invalid @endif" placeholder="{{__('admin.placeholder_text',['name'=> __('global.new_password_confirmation')])}}" required>
                                    <div class="input-group-append">
                                        <button type="button" class="input-group-text show-password"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="single_contact_form d-flex">
                                <button type="submit" class="btn primary-btn text-uppercase ml-auto"> {{__('global.change_password')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade crop-modal" id="profile-pic-chooser" data-value="profile_image_copper">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('global.crop_image')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div style="width: 100%; height: 400px">
                            <img class="profile-pic-chooser-image" alt="user image chooser" src="{{$user->image != (new \App\Models\User())->image ? storage_asset($user->image): ''}}" style="max-width: 100%;display: block;max-height:100%">
                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            <button onclick="handleZoomIn()" class="btn primary-btn small-btn mr-3">
                                <i class="fas fa-search-plus"></i>
                            </button>
                            <button onclick="handleZoomOut()" class="btn primary-btn small-btn">
                                <i class="fas fa-search-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn primary-btn crop-image-btn" data-dismiss="modal"
                            onclick="doneCropping()">{{ __('global.done_cropping') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script src="{{asset('plugins/cropper.min.js')}}"></script>
<script>
    let imageFileName, videoSize = 0, videoTypeVaildation = true;

    $("#video-input").on("change", function(e) {
        const file = e.target.files?.[0];
        if(file) {
            document.querySelector("#video-input-label span").innerHTML = file.name;
            if(file.size > 10300000 || (!file.type.startsWith("video") && !file.type.endsWith("ogg"))) {
                document.querySelector(`#video-input`).classList.remove("is-valid");
                document.querySelector(`#video-input`).classList.add("is-invalid");
                document.querySelector(`#video-input ~ .feedback`).classList.remove("valid-feedback");
                document.querySelector(`#video-input ~ .feedback`).classList.add("invalid-feedback");
                if(file.size > 10300000)
                    document.querySelector(`#video-input ~ .feedback`).textContent = "{{ __('global.video_size_limit') }}";
                else
                    document.querySelector(`#video-input ~ .feedback`).textContent = "{{ __('global.video_type_validation') }}";
                videoTypeVaildation = false;
            } else {
                document.querySelector(`#video-input`).classList.add("is-valid");
                document.querySelector(`#video-input`).classList.remove("is-invalid");
                document.querySelector(`#video-input ~ .feedback`).classList.add("valid-feedback");
                document.querySelector(`#video-input ~ .feedback`).classList.remove("invalid-feedback");
                document.querySelector(`#video-input ~ .feedback`).textContent = "";
                videoTypeVaildation = true;
            }
            videoSize = file.size;
        } else {
            videoSize = 0;
            videoTypeVaildation = true;
        }
    })

    const checkVideoSize = (e) => {
        e.preventDefault();
        console.log(videoSize, videoTypeVaildation)
        if(videoSize < 10300000 && videoTypeVaildation)
            e.target.submit();
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(".profile-pic-chooser-image").attr("src", e.target.result);
                $("#profile-pic-chooser").modal("show")
            };
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
    $("#image-input").on("change", function(e) {
        let preview_div = $(this).prev($('.crop-preview')).first();
        let data_target = preview_div.attr('data-target');
        readURL(this);
        preview_div.attr('onclick', "$('" + data_target + "').modal('show')");
        if(e.target.files?.[0]) imageFileName = e.target.files[0]?.name;
    })

    const profile_image_copper = {
        cropper: null,
        autoCropArea: 0.8,
        cropping_data: {},
        input: 'image_cropping',
        preview: '.profile-preview-div'
    };

    let cropper;
    
    $("#profile-pic-chooser").on('shown.bs.modal', function() {
        const img = document.querySelector(".profile-pic-chooser-image")
        if (cropper) {
            cropper.replace(img.src)
            return;
        }
        cropper = new Cropper(img, {
            autoCropArea: profile_image_copper.autoCropArea,
            data: profile_image_copper.cropping_data,
            aspectRatio: 1 / 1,
            minCropBoxWidth:200,
            cropBoxResizable: false,
            crop(event) {
                profile_image_copper.cropping_data = event.detail;
            }
        })
    })

    const doneCropping = () => {
        let url;
        const croppedCanvas = cropper.getCroppedCanvas();
        const id = profile_image_copper.preview;
        
        $('input:hidden[name=image_cropping]').val(croppedCanvas.toDataURL())
        document.querySelector("#image-input-label span").innerHTML = imageFileName;
        getRoundedCanvas(croppedCanvas).toBlob(blob => {
            url = URL.createObjectURL(blob)
            $(`.profile-pic-image`).attr("src", url)
        });
    }

    function getRoundedCanvas(sourceCanvas) {
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');
        var width = sourceCanvas.width;
        var height = sourceCanvas.height;

        canvas.width = width;
        canvas.height = height;
        context.imageSmoothingEnabled = true;
        context.drawImage(sourceCanvas, 0, 0, width, height);
        context.globalCompositeOperation = 'destination-in';
        context.beginPath();
        context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
        context.fill();
        return canvas;
    }

    $(".crop-image-btn").on("click", function() {
        if (cropper) {
            let url;
            getRoundedCanvas(cropper.getCroppedCanvas()).toBlob(blob => {
                url = URL.createObjectURL(blob)
                $(`${profile_image_copper.preview} img`).attr("src", url)
            });
        }
    });
    document.getElementById("revertToDefaultImage").onchange = function() {
        const status = event.target.checked;
        const image = document.getElementById('image-input');
        const preview = document.getElementById('crop-preview');
        if (status) {
            image.disabled = true;
            preview.classList.add('disabled-overlay')
        } else {
            image.disabled = false;
            preview.classList.remove('disabled-overlay')
        }
    };

    $(document).on('change', '.check_error', function() {
        let value = $(this).val();
        if (value.length > 0) {
            $(this).removeClass('required_error');
        } else {
            $(this).addClass('required_error');
        }
    })

    // Zoom in image in crop box
    const handleZoomIn = () => {
        cropper.zoom(0.1)
    }

    // Zoom out image in crop box
    const handleZoomOut = () => {
        cropper.zoom(-0.1)
    }

    // Toggle password input
    const showPasswordBtns = document.querySelectorAll(".show-password");
    showPasswordBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            const inputElm = btn.parentElement.previousElementSibling;
            if (inputElm.type === "password") inputElm.type = "text";
            else inputElm.type = "password"
        })
    })

    // Input website
    document.getElementById("website-input").addEventListener("blur", (e) => {
        const websiteRegex = /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g;
        const isValid = websiteRegex.test(e.target.value);
        if (!isValid) $(e.target).popover("show")
        else $(e.target).popover("dispose")
    })
</script>
@endsection