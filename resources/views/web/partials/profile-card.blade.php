<div class="single_team_member style_2 d-flex flex-column h-100" data-id="{{$profile->employee_id}}">
    <div class="text-center">
        <a href="{{route('web.profile', ['username'=>$profile->username])}}">
            <img src="{{asset('storage/uploads/profile/short-card-'.$profile->username.'.png')}}" alt="profile image" class="img-fluid">
        </a>
    </div>
    <div class="team_member_info">
        <h4><a href="{{route('web.profile', ['username'=>$profile->username])}}">{{$profile->full_name}}</a></h4>
        @if($profile->account_type == 'employee')
            <p>{{$profile->position}}</p>
        @else
            <p>{{__('global.'.$profile->account_type)}}</p>
        @endif
    </div>
</div>
