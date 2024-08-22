@section('title','Favourite Playlists')
@extends('frontend.layouts.master')
@section('content')
    <!--------------------------
        Favourite Playlists START
    --------------------------->

    <div class="top-artist-page">
      <div class="container">
        <div class="breadCrums">
          <ul>
            <li><a href="{{ url('/') }}">fanclub</a></li>
            <li>My Playlists</li>
          </ul>
        </div>
        <h4>My Playlists</h4>
        <div class="row">
        @foreach($content->playlistData as $key=>$row)
          <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
            <a href="{{url('my-playlist/'.$row->playlistSlug)}}" class="fanclub-playlist-box">
              <img src="{{$row->playListIcon}} " alt="" />
              <p class="s1">{{$row->playlistName}}</p>
            </a>
          </div>
          @endforeach
        </div>
      </div>
    </div>

@endsection
@section('footscript')

@endsection
