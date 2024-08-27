<div class="w-100 text-center mb-2">
    <img src="{{storage_asset($user->image)}}" alt="profile image" class="rounded-circle" style="width: auto; max-width: 100%; max-height:250px; ">
    <div class="single_contact_form mt-3">
        <label class="font-weight-bold" style="color: var(--secondary-color);">{{__('global.username')}}: <span class="font-weight-normal">{{inp_value($user, 'username')}} </span> </label>
    </div>
</div>
<nav class="nav flex-column side-nav p-2">
    <a class="nav-link {{Str::contains(request()->route()->getName(), 'account.edit') ? 'active' : ''}}" href="{{route('account.edit')}}">{{__('global.edit_details')}}</a>
    <a class="nav-link {{Str::contains(request()->route()->getName(), 'account.status') ? 'active' : ''}}" href="{{route('account.status')}}">{{__('global.account_status')}}</a>
    @if($user->account_type != 'employee')
    <a class="nav-link {{Str::contains(request()->route()->getName(), 'account.danger') ? 'active' : ''}}" href="{{route('account.danger')}}">{{__('global.danger_zone')}}</a>
    @endif
</nav>
