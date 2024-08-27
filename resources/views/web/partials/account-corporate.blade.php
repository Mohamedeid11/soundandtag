<!--Bussiness Section-->
<div class="row row-list ">
    <div class="single_contact_form form-group col-6">
        <label for="business-name-input">{{__('global.business_name')}} <sup class="required">*</sup></label>
        <input type="text" name="business_name" id="business-name-input" required class="form-control cu_input check_error @if(! $user->name) required_error @endif @if($errors->has('business_name')) is-invalid @endif " placeholder="{{__('admin.placeholder_text', ['name'=>__('global.business_name')])}}" value="{{inp_value($user, 'name')}}">
        @if($errors->has('business_name'))
        <div class="invalid-feedback">
            @foreach($errors->get('business_name') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>

    <div class="single_contact_form form-group col-6">
        <label for="business_name_meaning-input">{{__('global.business_name_meaning')}}</label>
        <input type="text" name="business_name_meaning" id="business_name_meaning-input" class="form-control cu_input @if($errors->has('business_name_meaning')) is-invalid @endif " placeholder="{{__('admin.placeholder_text', ['name'=>__('global.business_name_meaning')])}}" value="{{inp_value_for_record($records['0']->record,'business_name_meaning', 'meaning')}}">
        @if($errors->has('business_name_meaning'))
        <div class="invalid-feedback">
            @foreach($errors->get('business_name_meaning') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>
</div>


<div class="row row-list ">
    <!--Email -->
    <div class="single_contact_form form-group col-6">
        <label for="email-input">{{__('global.email')}} <sup class="required">*</sup></label>
        <input type="text" name="email" id="email-input" class="form-control cu_input @if(! $user->email) required_error @endif check_error @if($errors->has('email')) is-invalid @endif" disabled placeholder="{{__('admin.placeholder_text', ['name'=>__('global.email')])}}" value="{{inp_value($user, 'email')}}" required>
        @if($errors->has('email'))
        <div class="invalid-feedback">
            @foreach($errors->get('email') as $error)
            <span>{{$error}}</span>
            @endforeach
        </div>
        @endif
    </div>
    <!-- Phone-->
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
    <!--Address -->
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
    <!--Country -->
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

<div class="single_contact_form form-group">
    <label for="website-input">{{__('global.website')}}</label>
    <input type="text" name="website" id="website-input" class="form-control cu_input @if($errors->has('website')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.website')])}}" value="{{inp_value($user, 'website')}}" data-container="body" data-toggle="popover" data-placement="bottom" data-content="You must type a valid URL">
    @if($errors->has('website'))
    <div class="invalid-feedback">
        @foreach($errors->get('website') as $error)
        <span>{{$error}}</span>
        @endforeach
    </div>
    @endif
</div>


<!--Biography Section -->
<div class="single_contact_form form-group">
    <label for="biography-input">{{__('global.company_profile')}}</label>
    <textarea type="text" name="biography" id="biography-input" class="form-control cu_input @if($errors->has('biography')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.biography')])}}">{{inp_value($user, 'biography')}}</textarea>
    @if($errors->has('biography'))
    <div class="invalid-feedback">
        @foreach($errors->get('biography') as $error)
        <span>{{$error}}</span>
        @endforeach
    </div>
    @endif
</div>
