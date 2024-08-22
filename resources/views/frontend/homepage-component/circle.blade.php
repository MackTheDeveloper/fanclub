@if (Auth::check())
    @php($authenticateClass = '')
@else
    @php($authenticateClass = ' loginBeforeGo')
@endif

@php($urlPre = '')
@php($urlClick = '')
@if ($data->componentDynamicGroup->commonDetails->groupType == 1)
    @php($urlPre = 'artists')
@elseif($data->componentDynamicGroup->commonDetails->groupType == 2)
    @php($urlPre = 'songs')
@elseif($data->componentDynamicGroup->commonDetails->groupType == 4)
    @php($urlPre = 'categories')
@elseif($data->componentDynamicGroup->commonDetails->groupType == 5)
    @php($urlPre = 'languages')
@endif

<div class="fanclub-artist section" id="{{ $data->componentSlug }}">
    <div class="container-fluid">
        <div class="slider-header">
            <h5>{{ $data->componentName }}</h5>
            @if ($data->componentDynamicGroup->commonDetails->viewAll &&
                $data->componentDynamicGroup->commonDetails->viewAll == 1)
                @if ($data->componentDynamicGroup->commonDetails->groupType == 1)
                    <a href="{{ route('allArtists') }}"
                        class="a">See All</a>
                @elseif($data->componentDynamicGroup->commonDetails->groupType == 2)
                    <a href="{{ route('allSongs') }}"
                        class="a">See All</a>
                    @php($urlClick = route('allSongs'))
                @else
                    <a href="{{ url($urlPre . '/' . $data->componentDynamicGroup->commonDetails->DynamicGroupSlug) }}"
                        class="a">See All</a>
                @endif
            @else
                @php($urlClick = url($urlPre . '/' . $data->componentDynamicGroup->commonDetails->DynamicGroupSlug))
                <a href="{{ url($urlPre . '/' . $data->componentDynamicGroup->commonDetails->DynamicGroupSlug) }}"
                    class="a">See All</a>
            @endif
        </div>
    </div>
    <div class="rounded-img-carousel">
        <div class="owl-carousel owl-theme">
            @if ($data->componentDynamicGroup->commonDetails->groupType == 2)
                @foreach ($data->componentDynamicGroup->data as $key2 => $row2)
                    {{-- <a {{ $authenticateClass == '' && $urlPre == 'songs' ? 'data-song-id=' . $row2->Id : '' }}
                        href="{{ $authenticateClass ? 'javascript:void(0)' : $row2->detailUrl }}"
                        class="item{{ $authenticateClass ?: ($urlPre == 'songs' ? ' playSingleSongInPlayer' : '') }}"> --}}
                    <a href="{{ $urlClick }}"
                        class="item">
                        <img src="{{ $row2->Icon }}">
                        <p class="s1">{{ $row2->Name }}</p>
                    </a>
                @endforeach
            @else
                @foreach ($data->componentDynamicGroup->data as $key2 => $row2)
                    <a {{ $authenticateClass == '' && $urlPre == 'songs' ? 'data-song-id=' . $row2->Id : '' }}
                        href="{{ $row2->detailUrl }}"
                        class="item">
                        <img src="{{ $row2->Icon }}">
                        <p class="s1">{{ $row2->Name }}</p>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</div>
