@extends('web.layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/web/about.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/side-nav.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/card.css?v=1')}}" />
    <link href="https://goSellJSLib.b-cdn.net/v1.6.2/css/gosell.css" rel="stylesheet" />
    <script type="text/javascript" src="https://goSellJSLib.b-cdn.net/v1.6.2/js/gosell.js" id="go-sell-script"></script>
    <script type="text/javascript" src="https://credimax.gateway.mastercard.com/checkout/version/57/checkout.js" id="credi-max-script"></script>


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
                        <h4 class="mb-4">{{__("global.account_status")}}</h4>
                        <div class="card mb-3">
                            <div class="card-header font-weight-bold">
                                {{__("global.account_type")}}
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    {{__('global.your_account_type_is')}} : <strong>{{__('global.'.$user->account_type)}}  </strong>
                                    <sub class="text-warning mt-2 "><i class="fa fa-exclamation-triangle"></i> {{__('global.account_type_cannont_change')}}</sub>
                                </p>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header font-weight-bold">
                                {{__("global.general_status")}}
                            </div>
                            <div class="card-body">
                                @if (! empty($reasons))
                                    <h5 class="card-title text-danger"><i class="fa fa-times"></i> {{__("global.profile_not_public")}}</h5>
                                    <p class="card-text">
                                        @foreach($reasons as $reason)
                                            <label class="alert alert-danger w-100 d-flex flex-column">
                                                <strong>{{$reason->get('title')}}</strong>
                                                <span>{{$reason->get('text')}}</span>
                                                <a href="{{$reason->get('url')}}" target="_blank" class="ml-auto text-white {{$reason->get('class')}} @if($reason['type'] == 'payment') scroll-to-payment @endif">@if($reason->get('url_text') != '')<i class="text-white fa fa-check"></i>@endif {{$reason->get('url_text')}}</a>
                                            </label>
                                        @endforeach
                                    </p>
                                @else
                                    @if($user->hidden)
                                        <h5 class="card-title text-danger"><i class="fa fa-times"></i> {{__("global.profile_hidden")}}</h5>
                                        <p class="card-text text-right">
                                        <form class="d-flex" method="POST" action="">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-success ml-auto"><i class="fa fa-check"></i> {{__("global.publish_profile")}}</button>
                                        </form>
                                        </p>
                                    @else
                                        <h5 class="card-title text-success"><i class="fa fa-check"></i> {{__("global.profile_public")}}</h5>
                                        <p class="card-text text-right">
                                        <form class="d-flex" method="POST" action="">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger ml-auto"><i class="fa fa-times"></i> {{__("global.hide_profile")}}</button>
                                        </form>
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="card payment-section" id="payment_section">
                            <div class="card-header font-weight-bold">
                                {{__("global.payments")}}
                                @if($user->validity && $user->account_type != 'employee')
                                    <a class="float-right" target="_blank" role="button" href="{{route('download_invoice_pdf')}}" title="{{__('global.download_invoice')}}"><i class="fa fa-download" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="card-body">
                                @if ($trial !== false)
                                    <h5 class="card-title text-danger"><i class="fa fa-times"></i> {{__("global.trial_period")}}
                                        <strong>({{$trial}} {{__("global.days_remaining")}})</strong> </h5>
                                    @if($user->account_type === 'corporate' && $user->items > 0)
                                        <table class="table table-bordered text-light">
                                            <tbody>
                                            <tr>
                                                <th>{{__("global.employees")}}</th>
                                                <td>{{$user->employees()->count()}}/{{$user->items}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                    <div class="card-text text-right">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12 text-right">
                                                    <button data-toggle="modal" data-target="#payment-modal" class="btn btn-success ml-auto" id="pay-btn"><i class="fa fa-check"></i> {{__("global.go_premium")}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @if($user->validity)
                                        @if($user->plan->remaining < 30)
                                            <h5 class="card-title text-warning"><i class="fa fa-exclamation-triangle"></i> {{__("global.plan_about_to_expire")}}</h5>
                                        @else
                                            <h5 class="card-title text-success"><i class="fa fa-check"></i> {{__("global.valid_plan")}}</h5>
                                        @endif
                                        <table class="table table-bordered text-light">
                                            <tbody>
                                            <tr>
                                                <th>{{__("global.subscribed")}} </th>
                                                <td>{{$user->plan->payment->created_at}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{__("global.valid_until")}}</th>
                                                <td>{{$user->plan->end_date}} ({{ $user->plan->remaining }} days remaining)</td>
                                            </tr>
                                            <tr>
                                                <th>{{__("global.invoice")}}</th>
                                                <td>{{$user->plan->payment->transaction_id}}</td>
                                            </tr>
                                            @if($user->account_type === 'corporate' && $user->items > 0)
                                                <tr>
                                                    <th>{{__("global.employees")}}</th>
                                                    <td>{{$user->employees()->count()}}/{{$user->items}}</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                            <div class="card-text text-right">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col text-right">
                                                        @if($user->email_verified_at && $user->full_name && $user->country)
                                                            @if($user->can_renew)
                                                                <button data-toggle="modal" data-target="#payment-modal" class="btn btn-success ml-auto"><i class="fa fa-check"></i> {{__("global.renew")}}</button>
                                                            @endif
                                                            @if($user->can_upgrade)
                                                                <button data-toggle="modal" data-target="#upgrade-payment-modal" class="btn btn-success ml-auto"><i class="fa fa-check"></i> {{__("global.upgrade")}}</button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                            </div>
                                    @else
                                        @if (count($user->user_plans) == 0)
                                            <h5 class="card-title text-danger"><i class="fa fa-times"></i> {{__("global.plan_not_valid")}}</h5>
                                            <p class="card-text text-light">
                                                {{__("global.complete_sbscription")}}
                                            </p>
                                            <p class="card-text text-right">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12 text-right">
                                                        @if($user->email_verified_at && $user->full_name && $user->country)
                                                            <button data-toggle="modal" data-target="#payment-modal" class="btn btn-success ml-auto"><i class="fa fa-check"></i> {{__("global.go_ahead")}}</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            </p>
                                        @else
                                            <h5 class="card-title text-danger"><i class="fa fa-times"></i> {{__("global.plan_not_valid")}}</h5>
                                            <p class="card-text text-light">
                                                {{__("global.subscribe_to_be_public")}}
                                            </p>
                                            <p class="card-text text-right">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12 text-right">
                                                        <button data-toggle="modal" data-target="#payment-modal" class="btn btn-success ml-auto"><i class="fa fa-check"></i> {{__("global.go_ahead")}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                            </p>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="root"></div>
    <div class="modal fade" tabindex="-1" id="payment-modal" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-bottom">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('global.choose_plan')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-5" style="background: var(--background)">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            @foreach($plans as $plan)
                                @if($plan->priceForUser($user) > 0)
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        @if((! $user->plan && $plan->id == $user->plan_id) || ($user->plan && $plan->id == $user->plan->plan_id))
                                        <div class="card w-100 active h-100" onclick="activePlan(this)">
                                            <div class="card-body text-center border-top d-flex flex-column">
                                                @if($user->plan)
                                                    <span class="d-block text-primary h8 mb-3 m-auto text-decoration">{{__('global.current')}}</span>
                                                @endif
                                                <h6 class="card-title text-light">{{$plan->price}} {{__('global.USD')}}</h6>
                                                <h6 class="card-subtitle mb-2 text-muted mb-3">{{__('global.'.$plan->period)}}</h6>
                                                <p class="card-text mb-5">{{$plan->account_type == 'corporate' ? $plan->items. " Employees" : '' }}</p>
                                                <button onclick="choosePlanCredi(this, '{{$plan->id}}')" class="btn primary-btn align-self-center mt-auto">{{__('global.choose')}}</button>
                                            </div>
                                        </div>
                                        @else
                                        <div class="card w-100 h-100" onclick="activePlan(this)">
                                            <div class="card-body text-center border-top d-flex flex-column">
                                                <h5 class="card-title text-light">{{$plan->price}} {{__('global.USD')}}</h5>
                                                <h6 class="card-subtitle mb-2 text-muted mb-3">{{__('global.'.$plan->period)}}</h6>
                                                <p class="card-text mb-5">{{$plan->account_type == 'corporate' ? $plan->items. " Employees" : '' }}</p>
                                                <button onclick="choosePlanCredi(this, '{{$plan->id}}')" class="btn primary-btn align-self-center mt-auto">{{__('global.choose')}}</button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="upgrade-payment-modal" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-bottom">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('global.choose_plan')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-5" style="background: var(--background)">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            @if(count($upgradePlans) > 0)
                                @foreach($upgradePlans as $plan)
                                    @if($plan->priceForUser($user) > 0)
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                            @if($user->plan && $plan->id == $user->plan->plan_id)
                                            <div class="card w-100 active h-100" onclick="activePlan(this)">
                                                <div class="card-body text-center border-top d-flex flex-column">
                                                    @if(count($upgradePlans) == 1)
                                                        <span class="d-block text-primary h8 mb-3 m-auto text-decoration">{{__('global.current')}}</span>
                                                        <h6 class="card-title text-light">{{$plan->price}} {{__('global.USD')}}</h6>
                                                        <h6 class="card-subtitle mb-2 text-muted mb-3">{{__('global.'.$plan->period)}}</h6>
                                                        <h6 class="text-light border-bottom">{{__('global.upgrade')}}: {{$plan->priceForUser($user)}} {{__('global.USD')}}</h6>
                                                        <p class="card-text mb-5">{{$plan->account_type == 'corporate' ? $plan->items. " Employees" : '' }}</p>
                                                        <button onclick="choosePlanCredi(this, '{{$plan->id}}', true)" class="btn primary-btn align-self-center mt-auto">{{__('global.choose')}}</button>
                                                    @else
                                                        <span class="d-block text-primary h8 mb-3 m-auto text-decoration">{{__('global.current')}}</span>
                                                        <h6 class="card-title text-light">{{$plan->price}} {{__('global.USD')}}</h6>
                                                        <h6 class="card-subtitle mb-2 text-muted mb-3">{{__('global.'.$plan->period)}}</h6>
                                                        <p class="card-text mb-5">{{$plan->account_type == 'corporate' ? $plan->items. " Employees" : '' }}</p>
                                                        <button class="btn primary-btn align-self-center mt-auto" disabled>{{__('global.choose')}}</button>
                                                    @endif
                                                </div>
                                            </div>
                                            @else
                                            <div class="card w-100 h-100" onclick="activePlan(this)">
                                                <div class="card-body text-center border-top d-flex flex-column">
                                                    <h6 class="card-title text-light">{{$plan->price}} {{__('global.USD')}}</h6>
                                                    <h6 class="card-subtitle mb-2 text-muted mb-3">{{__('global.'.$plan->period)}}</h6>
                                                    <h6 class="text-light border-bottom">{{__('global.upgrade')}}: {{$plan->priceForUser($user)}} {{__('global.USD')}}</h6>
                                                    <p class="card-text mb-5">{{$plan->account_type == 'corporate' ? $plan->items. " Employees" : '' }}</p>
                                                    <button onclick="choosePlanCredi(this, '{{$plan->id}}', true)" class="btn primary-btn align-self-center mt-auto">{{__('global.choose')}}</button>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <h3 class="text-white">{{__('global.no_upgrade')}}</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('web.partials.axiosinit')
    <script>
        @if ($trial !== false || $user->can_renew || $user->can_upgrade || ! $user->plan)
            function pay(){
                // event.preventDefault();
                goSell.openLightBox();
            }
            function activePlan(e) {
                document.querySelectorAll(".card").forEach(elm => elm.classList.remove('active'))
                e.classList.add("active");
            }
            function choosePlanCredi(e, plan, upgrade = false) {
                const data = {
                    'plan': plan
                };
                data['upgrade'] = upgrade;
                if (upgrade) {
                    data['upgrade'] = true
                }
                axios.post('{{route('account.preparePayment.crediMax')}}', data, {withCredentials: true})
                    .then(function(response) {

                        if (response.data.status == 1) {
                            const script = load_js_credi(response.data.preparer.postUrl);
                            script.addEventListener('load', function() {

                                configCrediMax(response.data.preparer);

                            });
                        }
                    })
                    .catch(function(err) {
                        console.log(err);
                    });
            }

            function load_js_credi(postUrl) {
                document.getElementById("credi-max-script")?.remove();
                document.getElementById("root").innerHTML = "";
                var head = document.getElementsByTagName('head')[0];
                var script = document.createElement('script');
                script.id = "credi-max-script";
                script.type = 'text/javascript';
                script.src = "https://credimax.gateway.mastercard.com/checkout/version/57/checkout.js";
                script.dataset.err = "errorCallback";
                script.dataset.cancel = "{{route('account.status')}}";
                script.dataset.complete = postUrl;
                head.appendChild(script);
                return script;

            }

            function configCrediMax(payment_preparer) {

                Checkout.configure({
                    merchant: payment_preparer.gateway_merchant_id,
                    order: {
                        amount: payment_preparer.userSubscriptionPrice,
                        currency: payment_preparer.currency,
                        description: payment_preparer.labels['item_desc'],
                        id: payment_preparer.orderId
                    },
                    session: {
                        id: payment_preparer.sessionId
                    },
                    interaction: {
                        merchant: {
                            name: payment_preparer.labels['merchant_name'],
                            address: {
                                line1: payment_preparer.labels['address']
                            },
                            email: payment_preparer.labels['support_email'],
                            phone: payment_preparer.labels['suuport_phone'],
                            logo: payment_preparer.labels['logo']
                        },
                        operation: 'AUTHORIZE',
                        locale: payment_preparer.language, //ar_SAen_US
                        theme: 'default',
                        displayControl: {
                            billingAddress: 'HIDE', //OPTIONAL  READ_ONLY  MANDATORY
                            customerEmail: 'HIDE',
                            orderSummary: 'HIDE',
                            shipping: 'HIDE'
                        }
                    }
                });
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        Checkout.showLightbox();
                    });
                });

            }

        @endif
        goSell.showResult({
            callback: response => {
                console.log("callback", response);
            }
        });

        $(document).on('click', '.scroll-to-payment', function(event){
            event.preventDefault();
            $('html, body').animate({
                scrollTop: $("#payment_section").offset().top
            }, 2000);
        })

    </script>
@endsection
