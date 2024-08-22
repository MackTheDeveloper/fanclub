<!DOCTYPE html>
<html>
@include('frontend.include.head')

<body id="body">
    @php($themeType = Auth::check() ? Auth::user()->theme : '')
    <script type="text/javascript">
        var thhemeType = "{{ $themeType }}";
        if (!thhemeType) {
            thhemeType = localStorage.getItem('fanclubtheme');
        }
        if (thhemeType == 'dark') {
            var element = document.getElementById("body");
            element.classList.add("dark-theme");
        }
    </script>
    <!--------------------------
 HEADER START
 --------------------------->
    @yield('content')

</body>
@include('frontend.include.bottom')

</html>
