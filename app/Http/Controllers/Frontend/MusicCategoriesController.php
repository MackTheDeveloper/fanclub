<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\API\V1\MusicCategoryAPIController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MusicGenres;
use App\Http\Controllers\API\V1\MusicGenreAPIController;
use App\Models\MusicCategories;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Image;

class MusicCategoriesController extends Controller
{
  public function categoryDetails(Request $request,$slug="")
  {
    $musicGenre = MusicCategories::where('slug', $slug)->first();
    if ($musicGenre) {
      $api = new MusicCategoryAPIController();
      $data = $api->getCategoryById($musicGenre->id);
      $data = $data->getData();
      $content = $data->component;
      //$title = str_replace('-',' ',$exploaded[1]);
      $title = $musicGenre->name;
      // pre($content->genredata);
      return view('frontend.music-category.category-details', compact('content','title'));
    } else {
        abort(404, 'Page not found');
    }
  }
  public function loadmore(Request $request)
  {
    $id=$request->categoryId;
    $page=$request->page;
    if ($id) {
      $api = new MusicCategoryAPIController();
      $data = $api->getCategoryById($id,$page);
      $data = $data->getData();
      $content = $data->component;
      return view('frontend.music-category.loadmore-category-details', compact('content'));
    } else {
      abort(404, 'Page not found');
    }
  }
}
