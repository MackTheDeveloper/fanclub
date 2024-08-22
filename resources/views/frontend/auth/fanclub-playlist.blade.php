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
            <li><a href="{{ route('searchFront',$search) }}">search</a></li>
            <li>{{$search}}</li>
          </ul>
        </div>
        <h4>fanclub Playlists</h4>
        <div class="row">        
        @foreach($content['fanclubPlaylist']->fanclubPlaylistData->groupDetail as $key=>$row)
          <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
            <a href="{{url('songs/'.$row->groupSlug)}}" class="fanclub-playlist-box">
              <img src="{{$row->groupIcon}} " alt="" />
              <p class="s1">{{$row->groupName}}</p>
            </a>
          </div>
          @endforeach
        </div>
      </div>
    </div>

@endsection
@section('footscript')

@endsection
