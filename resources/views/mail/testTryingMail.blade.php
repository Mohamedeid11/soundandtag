@php
$message= "message";
$first_name="abdalla";
$user_id = 6;
$expires_at="2022-06-03";
@endphp

<div style="background:#edf0f3;">
    <div style="width:100%;text-align: center;">
        <div style="display:inline-block;max-width: 512px;text-align:center;background: white; padding: 15px;">
            Welcome to <br>
            <br>
            <img src="{{(env('URL_NO_SSL').'/images/img/logo-dark.png')}}" style="text-align: center !important;" width="250" alt="">
            <br><br>
            Dear {{$first_name}}<br>
            Thank you for trying our unique services, below is your Sound&Tag card, click on it to check your profile. <br>
            <br>
            <br>
            <a href="{{ route(
                'web.getTryingUserProfile', ['user_id' => $user_id]
            ) }}">
                <img src="{{(env('URL_NO_SSL').'/storage/uploads/profile/card-'.$user_id.'.jpg')}}" style="width: 100%;">
            </a>
            <small style="color: SlateBlue;">Your profile card</small>
            <br><br>

            You can always use your Sound&Tag card , here on this email , for the trial
            until {{$expires_at}} <br>
            Also, You can go <a href="{{route('web.get_register')}}" target="_blank" style="color: blue;">Register</a>

            <br><br>

            Thank You <br>
            <a href="{{route('web.get_landing')}}" target="_blank" style="color: blue;">www.soundandtag.com </a><br>
        </div>
    </div>
    <div style="width:100%;text-align: center;">
        <div style="display:inline-block;max-width: 512px;padding: 15px 0">
            <hr>
            <small>If you have any questions please feel free to contact us at support@soundandtag.com</small>
        </div>
    </div>
</div>