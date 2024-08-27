<!-- First Name Row -->
<div class="row row-list ">
    <div class="single_contact_form form-group col-6">
        <label for="full-name-input">{{__('global.first_name')}} <sup class="required">*</sup></label>
        <input type="text" name="full_name" id="full-name-input" class="form-control cu_input check_error @if($errors->has('full_name')) is-invalid @endif @if(! $user->full_name) required_error @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.name')])}}" value="{{inp_value($user, 'full_name')}}" required>
        @if($errors->has('full_name'))
        <div class="invalid-feedback">
            @foreach($errors->get('full_name') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>

    <div class="single_contact_form form-group col-6">
        <label for="first_name_meaning-input">{{__('global.first_name_meaning')}} </label>
        <input type="text" name="first_name_meaning" id="first_name_meaning-input" class="form-control cu_input @if($errors->has('first_name_meaning')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.first_name_meaning')])}}" value="{{inp_value_for_record($records['0']->record,'first_name_meaning', 'meaning')}}">
        @if($errors->has('first_name_meaning'))
        <div class="invalid-feedback">
            @foreach($errors->get('first_name_meaning') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>

</div>


<!-- Middle Name Row -->
<div class="row row-list ">
    <div class="single_contact_form form-group col-6">
        <label for="middle-name-input">{{__('global.middle_name')}} </label>
        <input type="text" name="middle_name" id="middle-name-input" class="form-control cu_input @if($errors->has('middle_name')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.middle_name')])}}" value="{{inp_value($user, 'middle_name')}}">
        @if($errors->has('middle_name'))
        <div class="invalid-feedback">
            @foreach($errors->get('middle_name') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>

    <div class="single_contact_form form-group col-6">
        <label for="middle_name_meaning-input">{{__('global.middle_name_meaning')}} </label>
        <input type="text" name="middle_name_meaning" id="middle_name_meaning-input" class="form-control cu_input @if($errors->has('middle_name_meaning')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.middle_name_meaning')])}}" value="{{inp_value_for_record($records['1']->record,'middle_name_meaning', 'meaning')}}">
        @if($errors->has('middle_name_meaning'))
        <div class="invalid-feedback">
            @foreach($errors->get('middle_name_meaning') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>
</div>


<!-- Last Name Row -->
<div class="row row-list ">

    <div class="single_contact_form form-group col-6">
        <label for="last-name-input">{{__('global.last_name')}} <sup class="required">*</sup></label>
        <input type="text" name="last_name" id="last-name-input" required class="form-control cu_input @if(! $user->last_name) required_error @endif check_error @if($errors->has('last_name')) is-invalid @endif " placeholder="{{__('admin.placeholder_text', ['name'=>__('global.last_name')])}}" value="{{inp_value($user, 'last_name')}}">
        @if($errors->has('last_name'))
        <div class="invalid-feedback">
            @foreach($errors->get('last_name') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>

    <div class="single_contact_form form-group col-6">
        <label for="last_name_meaning-input">{{__('global.last_name_meaning')}} </label>
        <input type="text" name="last_name_meaning" id="last_name_meaning-input" class="form-control cu_input @if($errors->has('last_name_meaning')) is-invalid @endif " placeholder="{{__('admin.placeholder_text', ['name'=>__('global.last_name_meaning')])}}" value="{{inp_value_for_record($records['2']->record,'last_name_meaning', 'meaning')}}">
        @if($errors->has('last_name_meaning'))
        <div class="invalid-feedback">
            @foreach($errors->get('last_name_meaning') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>

</div>

<!-- NickName Row -->
<div class="row row-list ">

    <div class="single_contact_form form-group col-6">
        <label for="nick_name-input">{{__('global.nick_name')}} </label>
        <input type="text" name="nick_name" id="nick_name-input" class="form-control cu_input @if($errors->has('nick_name')) is-invalid @endif " placeholder="{{__('admin.placeholder_text', ['name'=>__('global.nick_name')])}}" value="{{inp_value_for_record($records['3']->record,'nick_name', 'text_representation')}}">
        @if($errors->has('nick_name'))
        <div class="invalid-feedback">
            @foreach($errors->get('nick_name') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>

    <div class="single_contact_form form-group col-6">
        <label for="nick_name_meaning-input">{{__('global.nick_name_meaning')}} </label>
        <input type="text" name="nick_name_meaning" id="nick_name_meaning-input" class="form-control cu_input @if($errors->has('nick_name_meaning')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.nick_name_meaning')])}}" value="{{inp_value_for_record($records['3']->record,'nick_name', 'meaning')}}">
        @if($errors->has('nick_name_meaning'))
        <div class="invalid-feedback">
            @foreach($errors->get('nick_name_meaning') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>

</div>



<div class="row row-list ">
    <!-- Email -->
    <div class="single_contact_form form-group col-6">
        <label for="email-input">{{__('global.email')}} <sup class="required">*</sup></label>
        <input type="text" name="email" id="email-input" class="form-control cu_input check_error @if(! $user->email) required_error @endif @if($errors->has('email')) is-invalid @endif" disabled placeholder="{{__('admin.placeholder_text', ['name'=>__('global.email')])}}" value="{{inp_value($user, 'email')}}" required>
        @if($errors->has('email'))
        <div class="invalid-feedback">
            @foreach($errors->get('email') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>
    <!-- Phone -->
    <div class="single_contact_form form-group col-6">
        <label for="phone-input">{{__('global.phone')}}</label>
        <input type="text" name="phone" id="phone-input" class="form-control cu_input @if($errors->has('phone')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.phone')])}}" value="{{inp_value($user, 'phone')}}">
        @if($errors->has('phone'))
        <div class="invalid-feedback">
            @foreach($errors->get('phone') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>
</div>



<div class="row row-list ">
    <!-- Address -->
    <div class="single_contact_form form-group col-6">
        <label for="address-input">{{__('global.address')}}</label>
        <input type="text" name="address" id="address-input" class="form-control cu_input @if($errors->has('address')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.address')])}}" value="{{inp_value($user, 'address')}}">
        @if($errors->has('address'))
        <div class="invalid-feedback">
            @foreach($errors->get('address') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>
    <!-- Country -->
    <div class="single_contact_form form-group col-6">
        <label for="country-input">{{__('global.country')}} <sup class="required">*</sup></label>
        <select type="text" name="country_id" id="country-input" class="form-control cu_input check_error @if($errors->has('country_id')) is-invalid @endif @if(! $user->country_id) required_error @endif" required>
            <option value="">Select</option>
            @foreach($countries as $country)
            <option value="{{$country->id}}" {{select_value($user, 'country_id', $country->id)}}>{{$country->trans('name')}}</option>
            @endforeach
        </select>
        @if($errors->has('country_id'))
        <div class="invalid-feedback">
            @foreach($errors->get('country_id') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="row row-list ">

    <!-- Postion Section -->
    <div class="single_contact_form form-group col-6">
        <label for="position-input">{{__('global.position')}}<sup class="required">*</sup></label>
        <input type="text" name="position" id="position-input" class="form-control cu_input check_error @if($errors->has('position')) is-invalid @endif @if(! $user->position) required_error @endif " placeholder="{{__('admin.placeholder_text', ['name'=>__('global.position')])}}" value="{{inp_value($user, 'position')}}" required>
        @if($errors->has('position'))
        <div class="invalid-feedback">
            @foreach($errors->get('position') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Interests Section -->

    <div class="single_contact_form form-group col-6">
        <label for="interests-input">{{__('global.interests')}}</label>
        <input type="text" name="interests" id="interests-input" class="form-control cu_input @if($errors->has('interests')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.interests')])}}" value="{{inp_value($user, 'interests')}}">
        @if($errors->has('interests'))
        <div class="invalid-feedback">
            @foreach($errors->get('interests') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>
</div>

<!-- Biography Section -->
<div class="single_contact_form form-group">
    <label for="biography-input">{{__('global.biography')}}</label>
    <textarea type="text" name="biography" id="biography-input" class="form-control cu_input @if($errors->has('biography')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.biography')])}}">{{inp_value($user, 'biography')}}</textarea>
    @if($errors->has('biography'))
    <div class="invalid-feedback">
        @foreach($errors->get('biography') as $error)
        <span>{{$error}}</span>
        @endforeach
    </div>
    @endif
</div>
