<nav class="nav flex-column side-nav">
    <a class="nav-link {{Str::contains(request()->route()->getName(), 'guides') ? 'active' : ''}}" href="{{route('web.get_guides')}}">{{__('global.guide')}}</a>
    <a class="nav-link {{Str::contains(request()->route()->getName(), 'faqs') ? 'active' : ''}}" href="{{route('web.get_faqs')}}">{{__('global.faq')}}</a>
    <a class="nav-link" target="_blank"
        href="{{ url('storage/app/public/defaults/guide.pdf') }}">{{ __('global.guide_single') }}</a>
</nav>
