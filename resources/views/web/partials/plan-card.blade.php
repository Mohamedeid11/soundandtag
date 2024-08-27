<div class="w-100 my-3" style="background: linear-gradient(68deg,#00101f,#031839 53%); border-radius: 10px">
    <div class="card-body text-center border-top">
        <h6 class="card-title text-light">{{ucfirst($plan->account_type)}} Plan {{$loop->index + 1}}</h6>
        <h6 class="card-title text-light">{{$plan->price}} {{__('global.USD')}}</h6>
        <h6 class="card-subtitle mb-2 text-muted mb-3">{{__('global.'.$plan->period)}}</h6>
        <p class="card-text">{{$plan->account_type == 'corporate' ? $plan->items. __('global.employees') : '' }}</p>
        @if(!(auth()->guard('user')->check()))
            <a href="{{route('web.get_register') . "?plan=" . $plan->id . "&account_type=" . $plan->account_type }}" class="primary-btn btn rounded">{{__('global.go_ahead')}}</a>
        @endif
    </div>
</div>
