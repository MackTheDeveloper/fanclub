<title>{{ config('app.name') }} | @yield('title')</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="keywords" content="@yield('metaKeywords')">
<meta name="description" content="@yield('metaDescription')">

<meta property="og:url" content="{{url()->current()}}" />
<meta property="og:type" content="product" />
<meta property="og:title" content="@yield('metaTitle')" />
<meta property="og:description" content="@yield('metaDescription')"/>

@hasSection('metaImage')
    <meta property="og:image" content="@yield('metaImage')" />
@else
    <meta property="og:image" content="{{asset('public/assets/frontend/img/og-img.png')}}" />
@endif
