<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Songs;
use App\Models\MusicCategories;
use App\Models\MusicLanguages;
use App\Models\MusicGenres;
use App\Models\Artist;
use Auth;
use Storage;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Aws\ElasticTranscoder\ElasticTranscoderClient;

class SongsController extends Controller
{
    public function index(Request $request)
    {
        $input = $request->all();
        $categories = MusicCategories::getList();
        $genres = MusicGenres::getList();
        $languages = MusicLanguages::getList();
        // $atists = Songs::getArtistAll();
        $artist_id = (isset($input['artist_id']))?$input['artist_id']:'';
        $artists = Artist::getList();
        $search = (isset($input['search']) ? $input['search'] : '');
        return view("admin.songs.index",compact('categories','genres','languages','artists','artist_id','search'));
    }

    public function list(Request $request)
    {
        $isDownloadAble = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_song_download');
        $isCommentList = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_song_comment_list');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_song_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['','', 'name', 'users.firstname','','file_type','','','','', 'duration','release_date','created_at','num_likes','num_views','num_downloads'];


        $total = Songs::selectRaw('count(*) as total')->leftjoin('users','songs.artist_id','users.id')->whereNull('users.deleted_at')->whereNull('songs.deleted_at');
        $query = Songs::selectRaw('songs.*,users.firstname,users.lastname')->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->leftjoin('users','songs.artist_id','users.id');
        $filteredq = Songs::selectRaw('songs.*,users.firstname,users.lastname')->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->leftjoin('users','songs.artist_id','users.id');
        

        
        if (isset($request->category)) {
            $query->whereRaw("FIND_IN_SET(?, categories) > 0", [$request->category]);
            $filteredq->whereRaw("FIND_IN_SET(?, categories) > 0", [$request->category]);
        }
        if (isset($request->genre)) {
            $query->whereRaw("FIND_IN_SET(?, genre) > 0", [$request->genre]);
            $filteredq->whereRaw("FIND_IN_SET(?, genre) > 0", [$request->genre]);
        }
        if (isset($request->language)) {
            $query->whereRaw("FIND_IN_SET(?, languages) > 0", [$request->language]);
            $filteredq->whereRaw("FIND_IN_SET(?, languages) > 0", [$request->language]);
        }
        if (isset($request->artist)) {
            $query->where("artist_id", $request->artist);
            $filteredq->where("artist_id", $request->artist);
            $total->where("artist_id", $request->artist);
        }
        $total = $total->first();
        $totalfiltered = $total->total;
        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('artist', function ($qry) use ($search) {
                        $qry->where(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%');
                    });
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('artist', function ($qry) use ($search) {
                        $qry->where(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%');;
                    });
            });
            $filteredq = $filteredq->selectRaw('count(*) as total')->first();
            $totalfiltered = $filteredq->total;
        }
        // if (isset($request->is_active)) {
        //     $filteredq = $filteredq->where('is_active', $request->is_active);
        //     $query = $query->where('is_active', $request->is_active);
        // }
        // if (!empty($request->startDate) && !empty($request->endDate)) {
        //     $startDate = date($request->startDate);
        //     $endDate = date($request->endDate);
        //     $filteredq = $filteredq->where(function($q) use ($startDate,$endDate){
        //         $q->whereBetween('created_at', [$startDate, $endDate]);
        //     });
        //     $query = $query->where(function($q) use ($startDate,$endDate){
        //         $q->whereBetween('created_at', [$startDate, $endDate]);
        //     });
        // }
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        // pre($query);
        // if ($search != '') {
        //     $query->where(function ($query2) use ($search) {
        //         $query2->where('categories_data','like', '%' . $search . '%');
        //     });
        //     $filteredq->where(function ($query2) use ($search) {
        //         $query2->where('categories_data','like', '%' . $search . '%');
        //     });
        // }
        $data = [];
        foreach ($query as $key => $value) {
            // $isActive = '';
            $action = '';
            $downLoadUrl = route('songDownload', $value->id);
            $commentUrl = route('songComments', $value->id); 
            // if ($value->is_active == 1) {
            //     $isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            // } else {
            //     $isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            // }

            $subaction = ($isDownloadAble)?'<li class="nav-item">'
                    .'<a class="nav-link" href="' . $downLoadUrl . '">Download</a>'
                    .'</li>':'';
            
            $subaction .= ($isDeletable) ? '<li class="nav-item">'
                    . '<a class="nav-link song_delete" data-id="' . $value->id . '">Delete</a>'
                    . '</li>' : '';
            $subaction .=  ($isCommentList)?'<li class="nav-item">'
            .'<a class="nav-link showCommentList" data-id="' . $value->id . '">Comments</a>'
        .'</li>':'';
            if ($subaction ){
                $action .= '<div class="d-inline-block dropdown">'
                    .'<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                        .'<span class="btn-icon-wrapper pr-2 opacity-7">'
                            .'<i class="fa fa-cog fa-w-20"></i>'
                        .'</span>'
                    .'</button>'
                    .'<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                        .'<ul class="nav flex-column">'
                        .$subaction
                        .'</ul>'
                    .'</div>'
                .'</div>';
            }
            $mySubscriptions = $value->subscriptionPlan?$value->subscriptionPlan->subscription_name:'-';
            $icon = '<img width="50" height="50" src=' . $value->icon . '>';
            $data[] = [
                $value->id,
                $action,
                $value->name,
                $value->firstname.' '.$value->lastname,
                $icon,
                ucfirst($value->file_type),
                $value->duration,
                $value->categories_data,
                $value->genres_data,
                $value->languages_data,
                getFormatedDate($value->release_date),
                getFormatedDate($value->created_at),
                $value->num_likes,
                $value->num_views,
                $value->num_downloads,
            ];
        }
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }


    public function downLoad($id){
        $song = Songs::findOrFail($id);
        $extension = explode('.',$song->file);
        $extension = end($extension);
        $name = $this->makeSongName($song->name);
        $downLoadName = $name.'.'.$extension;
        return response()->streamDownload(function () use($song){
            echo file_get_contents($song->file);
        }, $downLoadName);
    }


    public function add()
    {
        $url = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
        $images = [];
        $files = Storage::disk('s3')->files('images-test');
        pre($files);
        foreach ($files as $file) {
            $images[] = [
            'name' => str_replace('images/', '', $file),
            'src' => $url . $file
            ];
        }
        // $this->transcodeNow();
        pre($images);
        return view('admin.songs.form');
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $name = time() . $file->getClientOriginalName();
                $filePath = 'images/' . $name;
                $upload = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                pre($upload);
            }
            // echo "string";die;
            return redirect(config('app.adminPrefix').'/songs/index')->with($notification);
        } catch (\Exception $e) {
            pre($e->getMessage());
            return redirect(config('app.adminPrefix').'/songs/index');
        }
    }

    public function makeSongName($string){
        return str_replace(' ','_', trim($string));
    }

    public function transcodeNow(){
        //The below credentials we have already configured in .env file
        $credentials = array (
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        );
        $AWSClient = ElasticTranscoderClient::factory($credentials);//Method to get presets from AWS cloud
        $AWSClient->listPresets();
        // pre($AWSClient);
    }

    public function delete(Request $request)
    {
        $model = Songs::where('id', $request->song_id)->first();
        if (!empty($model)) {
            $model->delete();
            $result['status'] = 'true';
            $result['msg'] = "Song Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
}
