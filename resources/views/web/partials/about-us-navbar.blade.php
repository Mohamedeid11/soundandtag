<nav class="nav flex-column side-nav">
    <a class="nav-link {{ (request()->route()->getName() == 'web.get_about') ? 'active' : ''}}" href="{{route('web.get_about')}}">{{__('global.service_overview')}}</a>
    <a class="nav-link" target="_blank" href="{{url('storage/app/public/defaults/sound&tag-presentation.pdf')}}">{{__('global.presentation')}}</a>
    <a class="nav-link {{Str::contains(request()->route()->getName(), 'get_about_us') ? 'active' : ''}}" href="{{route('web.get_about_us')}}">{{__('global.about_us')}}</a>
</nav>