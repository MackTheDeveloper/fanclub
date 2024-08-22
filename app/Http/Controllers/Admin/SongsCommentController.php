<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Songs;
use App\Models\SongComments;
use App\Models\Artist;
use Auth;
use Storage;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;

class SongsCommentController extends Controller
{
    public function index(Request $request)
    {
        $input = $request->all();
        $song_id = (isset($input['song_id']))?$input['song_id']:'';
        $artist_id = $song_id?Songs::getArtist($song_id):'';
        $artist = Artist::getList();
        $songs = $artist_id?Songs::getSongs($artist_id):'';
        return view("admin.song_comments.index",compact('artist','song_id','songs','artist_id'));
    }

    public function list(Request $request)
    {
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_song_comment_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['','users.firstname','','','song_comments.created_at'];


        $total = SongComments::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = SongComments::selectRaw('song_comments.*,users.firstname,users.lastname')
                ->whereNull('song_comments.deleted_at')->has('song')->leftjoin('users','song_comments.user_id','users.id');
        $filteredq = SongComments::selectRaw('song_comments.*,users.firstname,users.lastname')->whereNull('song_comments.deleted_at')->has('song')->leftjoin('users','song_comments.user_id','users.id');
        $totalfiltered = $total->total;

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%');
            });
            $filteredq = $filteredq->selectRaw('count(*) as total')->first();
            $totalfiltered = $filteredq->total;
        }
        if (isset($request->songs)) {
            $query->whereIn("song_id", $request->songs);
            $filteredq->whereIn("song_id", $request->songs);
        }
        if (isset($request->artists)) {
            $artistId = $request->artists;
            $query->whereHas("song", function($qry) use ($artistId){
                $qry->whereIn('artist_id', $artistId);
            });
            $filteredq->whereHas("song", function($qry) use ($artistId){
                $qry->whereIn('artist_id', $artistId);
            });
        }
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date($request->startDate);
            $endDate = date($request->endDate);
            $filteredq = $filteredq->where(function($q) use ($startDate,$endDate){
                $q->whereBetween('song_comments.created_at', [$startDate, $endDate]);
            });
            $query = $query->where(function($q) use ($startDate,$endDate){
                $q->whereBetween('song_comments.created_at', [$startDate, $endDate]);
            });
        }
        
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        
        $data = [];
        foreach ($query as $key => $value) {
            // $isActive = '';
            $action = '';
            $commentUrl = route('songComments', $value->id);
            
            $subaction =  ($isDeletable)?'<li class="nav-item">'
            .'<a class="nav-link comment_view" data-id="' . $value->id . '">Comment</a>'
        .'</li>':'';
            $subaction .=  ($isDeletable)?'<li class="nav-item">'
            .'<a class="nav-link comment_delete" data-id="' . $value->id . '">Delete</a>'
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
            $data[] = [
                $value->id,
                $action,
                $value->firstname.' '.$value->lastname,
                $value->song->name,
                // $value->comment,
                strlen($value->comment) > 50 ? substr($value->comment,0,50)."..." : $value->comment,
                getFormatedDate($value->created_at),
                
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


    public function getSongList(Request $request)
    {
        $input = $request->all();        
        $data = Songs::whereIn('artist_id',$input)->get();
        return view('admin.song_comments.song_dropdown',compact('data'));
    }

    public function getComment($id)
    {
        $data = SongComments::where('id',$id)->first();
        return $data->comment;
    }

    public function delete(Request $request)
    {
        $model = SongComments::where('id', $request->comment_id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Song Comment Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
}
