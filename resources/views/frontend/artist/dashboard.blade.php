@section('title', 'Dashboard')
@extends('frontend.layouts.master')
@section('content')
    <!--------------------------
                ARTIST DASHBOARD START
        --------------------------->
    <div class="dashboard-page">
        <div class="container">
            <div class="dashboard-header">
                <div class="d-img-content">
                    <img src="{{ $content['artistComponent']->artistComponentData->image }}" alt="" />
                    <div class="d-content">
                        <h4>{{ $content['artistComponent']->artistComponentData->title }}</h4>
                        {{-- <span>Find the analysis of your profile here</span> --}}
                    </div>
                </div>
                <div class="view-btn-links">
					<div class="view-profile">
						{{-- <p>View as Public</p> --}}
						<p>Social Media link to your page.</p>
						<a href="javascript:void(0)" data-url="{{$content['artistComponent']->artistComponentData->profileUrl}}" class="copy-profile"><img src="{{ asset('public/assets/frontend/img/view-as-profile.svg') }} " alt="" /></a>
					</div>
					<a href="{{ route('SongUploadView') }}" class="fill-btn">Upload New Video</a>
				</div>
                
                {{-- <a href="{{ route('SongUploadView') }}" class="fill-btn" >Upload New Video</a> --}}
            </div>
            <div class="analytics-wrapper">
                <h5>Check out your analytics below</h5>
                <div class="analytics">
                    <div class="round-progress">
                        <div class="progress-svg">
                            <svg viewBox="0 0 36 36" class="circular-chart">
                                <defs>
                                    <linearGradient id="linear" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#E0208C" />
                                        <stop offset="100%" stop-color="#FA6400" />
                                    </linearGradient>
                                </defs>
                                <path class="circle"
                                    stroke-dasharray="{{ $content['artistAnalystics']->artistAnalysticsData->progress }}, 100"
                                    d="M18 2.0845
                                            a 15.9155 15.9155 0 0 1 0 31.831
                                            a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <h5>{{ $content['artistAnalystics']->artistAnalysticsData->progressText }}</h5>
                        </div>
                        <div class="progress-content">
                            <span>{{ $content['artistAnalystics']->artistAnalysticsData->progressDesc }}</span>
                            {{-- <span>Achieve more milestones to complete your profile.</span> --}}
                        </div>
                    </div>
                    <div class="progress-status-wrapper">
                        <div class="progress-status">
                            @foreach ($content['artistSubscription']->artistSubscriptionData->list as $key => $row)
                                {{-- <div class="{{ $key ? 'annual' : 'monthly' }}">
                                    <span>{{ str_replace('Subscriptions', 'Subs', $row->title) }}</span>
                                    <h5>{{ $row->count }}</h5>
                                </div> --}}
                                <div class="cal-box">
                                    <span>{{ str_replace('Subscriptions', 'Subs', $row->title) }}</span>
                                    <h5>{{ $row->count }}</h5>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="like-song-wrapper">
                        <div class="like-song">
                            <div class="likes">
                                <img src="{{ asset('public/assets/frontend/img/like-bg.svg') }}" alt="" />
                                <div class="like-content">
                                    <span>{{ $content['songsStatus']->songsStatusData->likeText }}</span>
                                    <h5>{{ $content['songsStatus']->songsStatusData->likeCount }}</h5>
                                </div>
                            </div>
                            <div class="songs">
                                <img src="{{ asset('public/assets/frontend/img/song-bg.svg') }}" alt="" />
                                <div class="song-content">
                                    <span>{{ $content['songsStatus']->songsStatusData->songText }}</span>
                                    <h5>{{ $content['songsStatus']->songsStatusData->songCount }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($content['artistAnalystics']->artistAnalysticsData->is_map_view == 0)
            <div class="upload-song-check">
				<div class="row-5">
                    @foreach ($content['artistAnalystics']->artistAnalysticsData->list as $key => $row)
					<div class="column">
						<div class="usc-box">
							@if ($row->completed == '1')
                                <img src="{{ asset('public/assets/frontend/img/ck-upload-green.svg') }}" alt="" />
                            @else
                                <img class="inactive" src="{{ asset('public/assets/frontend/img/ck-upload.svg') }}"
                                    alt="" />
                            @endif
                            <span>{{ $row->message }}</span>
						</div>
					</div>
                    @endforeach
				</div>
			</div>
            @else
            <div class="check-and-map">
                <div class="song-check-status">
                    @foreach ($content['artistAnalystics']->artistAnalysticsData->list as $key => $row)
                        <div class="usc-box">
                            @if ($row->completed == '1')
                                <img src="{{ asset('public/assets/frontend/img/ck-upload-green.svg') }}" alt="" />
                            @else
                                <img class="inactive" src="{{ asset('public/assets/frontend/img/ck-upload.svg') }}"
                                    alt="" />
                            @endif
                            <span>{{ $row->message }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="map-wrapper">
                    <h6>Active Subscribers</h6>
                    <div class="map-and-progress">
                        <div class="map-wrapper-box">
                            <div id="regions_div" style="width: 100%; height: auto;"></div>
                        </div>
                        <div class="progress-wrapper-box">
                            <div class="map-progress-header">
                                <p class="s1">Country</p>
                                <p class="s1">Subscribers</p>
                            </div>
                            <div class="progress-scroll">
                                @foreach ($content['artistAnalystics']->artistAnalysticsData->listCountryWiseCount as $key => $row)
                                <div class="map-progressbar-wrapper">
                                    <div class="mpw-header">
                                        <p class="s2">{{ $row->name }}</p>
                                        <span>{{ $row->value }}</span>
                                      </div>
                                    <div class="map-progressbar">
                                        <div class="map-progress-length" style="width: {{ $row->progressData }}%;">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="recent-upload songs-data">
                <div class="slider-header">
                    <h5>{{ $content['recentSongsSlider']->recentSongsSliderData->title }}</h5>
                    <a href="{{ route('songList') }}" class="a">See All</a>
                </div>
                <div class="row-5">
                    @foreach ($content['recentSongsSlider']->recentSongsSliderData->list as $key => $row)
                        @include('frontend.components.song-grid',['songId' => $row->id, 'icon' => $row->icon,'name' => $row->name, 'artistName' => $content['artistComponent']->artistComponentData->name, 'noViews' => $row->noViews,'noLikes' => $row->noLikes,'hideLikeViews' => 0,'artistId' => $row->artistId])
                        {{-- <div class="column">
                            <a href="javascript:void(0)" class="recent-box playSingleSongInPlayer" data-song-id={{ $row->id }}>
                                <img src="{{ $row->icon }}">
                                <p class="s1">{{ $row->name }}</p>
                                <div class="caption">
                                    <p>{{ $content['artistComponent']->artistComponentData->name }}</p>
                                </div>
                            </a>
                        </div> --}}
                    @endforeach
                </div>
            </div>

            <div class="recent-upload songs-data">
                <div class="slider-header">
                    <h5>{{ $content['topSongsSlider']->topSongsSliderData->title }}</h5>
                </div>
                <div class="row-5">
                    @foreach ($content['topSongsSlider']->topSongsSliderData->list as $key => $row)
                        @include('frontend.components.song-grid',['songId' => $row->id, 'icon' => $row->icon,'name' => $row->name, 'artistName' => $content['artistComponent']->artistComponentData->name, 'noViews' => $row->noViews,'noLikes' => $row->noLikes,'hideLikeViews' => 0,'artistId' => $row->artistId])
                        {{-- <div class="column">
                            <a href="javascript:void(0)" class="recent-box playSingleSongInPlayer" data-song-id={{ $row->id }}>
                                <img src="{{ $row->icon }}">
                                <p class="s1">{{ $row->name }}</p>
                                <div class="caption">
                                    <p>{{ $content['artistComponent']->artistComponentData->name }}</p>
                                </div>
                            </a>
                        </div> --}}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!--------------------------
                ARTIST DASHBOARD END
        --------------------------->
    @include('frontend.components.music-player.form-for-single-song')
@endsection
@section('footscript')
 <script src="{{ asset('public/assets/frontend/js/redirect-video-player.js') }}?r=090820200" data-base-url="{{ url('/') }}">
    </script>
<script>
@if ($content['artistAnalystics']->artistAnalysticsData->is_map_view == 1)
google.charts.load('current', {
  'packages':['geochart'],
});
google.charts.setOnLoadCallback(drawRegionsMap);

function drawRegionsMap() {
  var data = google.visualization.arrayToDataTable([
    ['Country', 'Subscribers'],
    @foreach ($content['artistAnalystics']->artistAnalysticsData->listCountryWiseCount as $key => $row)
        ['{{ $row->name }}', {{ $row->value }}],
        @endforeach
  ]);

  var options = {
    colorAxis: {
      colors: ['#ED4247', '#ED4247','#ED4247']
    },
    backgroundColor: 'transparent',
    legend: false,
    tooltip: {
      textStyle: {
        color: '#212121',
        fontSize: '12',
        fontName: "'Noto Sans JP', sans-serif"
      },
      showColorCode: true
    },
    datalessRegionColor: '#b3b3b3'
  };

  var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));
  chart.draw(data, options);
}
@endif
$(document).on('click','.copy-profile',function(e){
    e.preventDefault();
    var url = $(this).data('url');
    let msg = "Profile URL coppied to clipboard";
    navigator.clipboard.writeText(url)
    toastr.clear();
    toastr.options.closeButton = true;
    toastr.options.timeOut = 0;
    toastr.success(msg);
    setTimeout(function(){
        toastr.clear();
        var win = window.open(url, '_blank');
    }, 1000);
});

</script>
@endsection
