@if (Auth::check())
    @php($authenticateClass = '')
@else
    @php($authenticateClass = ' loginBeforeGo')
@endif
<div class="subscribe section" id="{{$bannerData->componentSlug}}">
		<div class="container-fluid">
			<div class="subscribe-block">
				<a class="{{ $bannerData->componentBannerUrlType == '6' ? $authenticateClass : '' }} {{$bannerData->componentBannerUrlType == '6' ? 'playSingleSongInPlayer' : ''}}" 
					href="{{$bannerData->componentBannerUrlType != '6' ? url($bannerData->componentBannerUrl) : 'javascript:void(0)'}}" 
					{{$bannerData->componentBannerUrlType == '6' ? "data-song-id=$bannerData->componentBannerUrlTypeId" : ""}}>
				<img src="{{$bannerData->componentBanner}}">
				</a>
			</div>
		</div>
	</div>
