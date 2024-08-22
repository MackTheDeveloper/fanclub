    @php($authRole = getAuthProps())
    <input type="hidden" name="current-song-slug" value="{{ $playerSong->playerSongData->data->songSlug }}">
    <input type="hidden" name="current-song-resolution" value="{{ $playerSong->playerSongData->data->currentResolution }}">
    {{-- <input type="hidden" class="songVideoDownload"
        value="{{ $playerSong->playerSongData->data->songVideoDownload }}" />
    <input type="hidden" class="songAudioDownload"
        value="{{ $playerSong->playerSongData->data->songAudioDownload }}" /> --}}
    <div class="video-loader-wrapper">
        <svg class="circular-loader"viewBox="25 25 50 50" >
            <circle class="loader-path" cx="50" cy="50" r="20" fill="none" stroke="#ED4247" stroke-width="3" />
        </svg>
    </div>
    <div class="video-header">
        <div class="audio-toggle-sec">
            <p class="s1">
                <hideData>Play</hideData> Audio Only
            </p>
            <div class="button r" id="button-1">
                <input type="checkbox" class="checkbox" id="switch">
                <div class="knobs"></div>
                <div class="layer"></div>
            </div>
        </div>
        <a href="{{ $playerSong->playerSongData->data->songVideoDownload }}" class="download-video">
            <img src="{{ asset('public/assets/frontend/img/download-video.svg') }}" alt="" />
        </a>
        {{-- <a href="javascript:void(0)" class="download-video">
            <img src="{{ asset('public/assets/frontend/img/download-video.svg') }}" alt="" />
        </a> --}}
        @if(Auth::check() && $authRole!=2)
        {{-- @if(Auth::check() && Auth::user()->role_id!=2) --}}
        <label class="heart video-likes">
            <input type="checkbox" data-id={{ $playerSong->playerSongData->data->songId }} class="songLikeDislike"
                {{ $playerSong->playerSongData->data->songLike == 1 ? 'checked' : '' }} value="yes" name="heart">
            <span class="heart-checkmark"></span>
        </label>
        @endif
    </div>
    <video id='video'>
        <source src="{{ $playerSong->playerSongData->data->songVideo }}" type='video/{{$supportMime}}'>
    </video>
    <audio class="hidden" id="audio" src="{{ $playerSong->playerSongData->data->songAudio }}">
    </audio>
    <img src="{{ asset('public/assets/frontend/img/video-placeholder2.png') }}" class="video-placeholder" alt="" />
    <div class="video-controller">
        <a href="javascript:void(0)" class="control-btn play-btn">
            <img src="{{ asset('public/assets/frontend/img/video-play.svg') }}" alt="" class="video-play" />
            <img src="{{ asset('public/assets/frontend/img/video-pause.svg') }}" alt="" class="video-pause" />
        </a>
        <div class="video-progress-wrapper">
            <div class="video-progress"></div>
        </div>
        <a href="javascript:void(0)" class="control-btn sound-btn">
            <div class="sound-btn-click">
                <img src="{{ asset('public/assets/frontend/img/video-sound.svg') }}" class="full-volume" alt="" />
                <img src="{{ asset('public/assets/frontend/img/half-volume.svg') }}" class="half-volume" alt="" />
                <img src="{{ asset('public/assets/frontend/img/mute-icon.svg') }}" class="mute-volume" alt="" />
            </div>
            <div class="sound-box-wrapper">
                <div class="sound-box">
                    <div class="volume-progress"></div>
                </div>
            </div>
        </a>
        <div class="dropdown c-dropdown settingDropdown">
            <button class="dropdown-toggle" data-bs-toggle="dropdown">
                <img src="{{ asset('public/assets/frontend/img/video-setting.svg') }}" alt=""
                    class="c-icon" />
            </button>
            <div class="dropdown-menu">
                <div class="video-main-menu">
                    <a class="dropdown-item play-speed-link">
                        <div class="video-menu-img">
                            <img src="{{ asset('public/assets/frontend/img/Playspeed.svg') }}" alt="" />
                            <span>Playback Speed</span>
                        </div>
                        <div class="video-menu-content">
                            <span>Normal</span>
                            <img src="{{ asset('public/assets/frontend/img/white-right-arrow.svg') }}" alt="" />
                        </div>
                    </a>
                    <a class="dropdown-item quality-link">
                        <div class="video-menu-img">
                            <img src="{{ asset('public/assets/frontend/img/quality.svg') }}" alt="" />
                            <span>Quality</span>
                        </div>
                        <div class="video-menu-content">
                            <span>Auto 720p</span>
                            <img src="{{ asset('public/assets/frontend/img/white-right-arrow.svg') }}" alt="" />
                        </div>
                    </a>
                </div>
                <div class="play-speed-content">
                    <div class="inner-content-header">
                        <img src="{{ asset('public/assets/frontend/img/left-white-arrow.svg') }}" alt=""
                            class="back-to-menu">
                        <span>Playback Speed</span>
                    </div>
                    <div class="video-content-scroll">
                        <label class="rightCk back-to-menu"><span>0.25</span>
                            <input type="radio" value="0.25" data-show="0.25x" name="speed">
                            <span class="right-checkmark"></span>
                        </label>
                        <label class="rightCk back-to-menu"><span>0.5</span>
                            <input type="radio" value="0.5" data-show="0.5x" name="speed">
                            <span class="right-checkmark"></span>
                        </label>
                        <label class="rightCk back-to-menu"><span>0.75</span>
                            <input type="radio" value="0.75" data-show="0.75x" name="speed">
                            <span class="right-checkmark"></span>
                        </label>
                        <label class="rightCk back-to-menu"><span>Normal</span>
                            <input type="radio" value="1" data-show="Normal" name="speed" checked>
                            <span class="right-checkmark"></span>
                        </label>
                        <label class="rightCk back-to-menu"><span>1.25</span>
                            <input type="radio" value="1.25" data-show="1.25x" name="speed">
                            <span class="right-checkmark"></span>
                        </label>
                        <label class="rightCk back-to-menu"><span>1.5</span>
                            <input type="radio" value="1.5" data-show="1.5x" name="speed">
                            <span class="right-checkmark"></span>
                        </label>
                        <label class="rightCk back-to-menu"><span>1.75</span>
                            <input type="radio" value="1.75" data-show="1.75x" name="speed">
                            <span class="right-checkmark"></span>
                        </label>
                        <label class="rightCk back-to-menu"><span>2</span>
                            <input type="radio" value="2" data-show="2x" name="speed">
                            <span class="right-checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="quality-content">
                    <div class="inner-content-header">
                        <img src="{{ asset('public/assets/frontend/img/left-white-arrow.svg') }}" alt=""
                            class="back-to-menu">
                        <span>Quality</span>
                    </div>
                    <div class="video-content-scroll">
                        @foreach ($player->playerData->quality as $key => $item)
                            <label class="rightCk back-to-menu"><span>{{ $item->value }}</span>
                                <input type="radio" value="{{ $item->key }}" data-show={{ $item->value }}
                                    name="quality" {{ $item->key == $playerSong->playerSongData->data->currentResolution ? 'checked' : '' }}>
                                <span class="right-checkmark"></span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <a href="javascript:void(0)" class="control-btn" id="pipButton">
            <img src="{{ asset('public/assets/frontend/img/full-screen.svg') }}" alt="" />
        </a>
        <a href="javascript:void(0)" class="control-btn fullscreen">
            <img src="{{ asset('public/assets/frontend/img/screen-size.svg') }}" alt="" class="full-screen-icon" />
            <img src="{{ asset('public/assets/frontend/img/Exit-Full-Screen.svg') }}" alt=""
                class="exit-screen-icon" />
        </a>
    </div>
