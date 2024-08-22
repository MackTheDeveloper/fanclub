<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MusicGenres;
use App\Http\Controllers\API\V1\MusicGenreAPIController;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Image;

class MusicGenresController extends Controller
{
  public function genreDetails(Request $request, $slug="")
  {
    $musicGenre = MusicGenres::where('slug', $slug)->first();
    if ($musicGenre) {
      $api = new MusicGenreAPIController();
      $data = $api->getGenreById($musicGenre->id);
      $data = $data->getData();
      $content = $data->component;
      //$title = str_replace('-',' ',$exploaded[1]);
      $title = $musicGenre->name;
      return view('frontend.music-genre.genre-details', compact('content','title'));
    } else {
        abort(404, 'Page not found');
    }
  }
  public function loadmore(Request $request)
  {
    $id=$request->GenreId;
    $page=$request->page;
    if ($id) {
      $api = new MusicGenreAPIController();
      $data = $api->getGenreById($id,$page);
      $data = $data->getData();
      $content = $data->component;
      return view('frontend.music-genre.loadmore-genre-details', compact('content'));
    } else {
      abort(404, 'Page not found');
    }
  }
}
