    @php($authRole = getAuthProps())
    <div class="song-list">
        <div class="song-list-header">
            <h6>{{ $page == 'single-song-in-player' ? 'Suggested Songs' : 'Next in Queue' }}</h6>
        </div>
        <div class="song-list-wrapper">
            <ul id="sortable">
                @foreach ($queueSongs->queueSongsData->list as $item)
                    <li class="{{ $item->activePlayingClass }}">
                        <a href="javascript:void(0)" class="drag-handle">
                            <img src="{{ asset('public/assets/frontend/img/movers.svg') }}" alt="">
                        </a>
                        <a href="javascript:void(0)" class="song-list-img playQueueSong"
                            data-song-id={{ $item->songId }}>
                            <img src="{{ $item->songIcon }}">
                        </a>
                        <div class="queue-name-detail playQueueSong" data-song-id={{ $item->songId }}>
                            <p class="s1">{{ $item->songName }}</p>
                            <span>{{ $item->artistName }}</span>
                        </div>
                        <div class="song-btn-block">
                            @if (Auth::check() && $authRole != 2)
                                {{-- @if (Auth::check() && Auth::user()->role_id != 2) --}}
                                <label class="heart">
                                    <input type="checkbox" data-id={{ $item->songId }} class="songLikeDislike"
                                        {{ $item->songLike == 1 ? 'checked' : '' }} value="yes" name="heart">
                                    <span class="heart-checkmark"></span>
                                </label>
                            @endif
                            @include('frontend.components.action-popup', [
                                'menus' => ['popupAddToPlaylist', 'popupDownload'],
                                'songId' => $item->songId,
                                'allData' => $item,
                            ])
                        </div>
                        <span class="last-duration">{{ $item->duration }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
