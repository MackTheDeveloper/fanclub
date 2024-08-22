<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Forums;
use App\Models\ForumComments;
use App\Models\GlobalLanguage;
use App\Models\Songs;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;


class ReviewsRatingsController extends Controller
{
    public function index()
    {
        $users = Artist::getArtistFullName();
        return view("admin.reviewsandratings.index",compact('users'));
    }

    public function list(Request $request)
    {
        $isCommentList = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_reviewandlisting_listing');
        $isApproveDisaprove = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_reviewandlisting_activeInactive');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_reviewandlisting_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['id','action','icon','t2.firstname','type','t1.firstname','email','ratings','reviews','created_at'];

        $total = Reviews::selectRaw('count(*) as total')->leftJoin('songs', 'songs.id', '=', 'reviews.song_id')
            ->leftJoin('users AS t1', 't1.id', '=', 'reviews.customer_id')
            ->leftJoin('users AS t2', 't2.id', '=', 'reviews.artist_id')
            ->whereNull('songs.deleted_at')->whereNull('t1.deleted_at')->whereNull('t2.deleted_at')->first();

        $query =  Reviews::whereNull('reviews.deleted_at')
            ->leftJoin('songs', 'songs.id', '=', 'reviews.song_id')
            ->leftJoin('users AS t1', 't1.id', '=', 'reviews.customer_id')
            ->leftJoin('users AS t2', 't2.id', '=', 'reviews.artist_id')
            ->whereNull('songs.deleted_at')->whereNull('t1.deleted_at')->whereNull('t2.deleted_at')
            ->select('t2.firstname as ArtistFirstName','t2.lastname as ArtistLastName','reviews.*', 't1.firstname as customerFirstName','t1.lastname as customerLastName','t1.email as email','songs.icon as icon','songs.name as songName');
        $filteredq = Reviews::whereNull('reviews.deleted_at')
            ->leftJoin('songs', 'songs.id', '=', 'reviews.song_id')
            ->leftJoin('users AS t1', 't1.id', '=', 'reviews.customer_id')
            ->leftJoin('users AS t2', 't2.id', '=', 'reviews.artist_id')
            ->whereNull('songs.deleted_at')->whereNull('t1.deleted_at')->whereNull('t2.deleted_at')
            ->select('t2.firstname as ArtistFirstName','t2.lastname as ArtistLastName','reviews.*', 't1.firstname as customerFirstName','t1.lastname as customerLastName','t1.email as email','songs.icon as icon','songs.name as songName');

        $totalfiltered = $total->total;

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where(DB::raw("CONCAT(t1.firstname,' ',t1.lastname)"), 'like', '%' . $search . '%') // customer fN
                    ->orWhere(DB::raw("CONCAT(t2.firstname,' ',t2.lastname)"), 'like', '%' . $search . '%') // customer lN
                    ->orWhere('songs.name', 'like', '%' . $search . '%')
                    ->orWhere('t1.email', 'like', '%' . $search . '%')
                    ->orWhere('reviews.created_at', 'like', '%' . $search . '%')
                    ->orWhere('reviews.ratings', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where(DB::raw("CONCAT(t1.firstname,' ',t1.lastname)"), 'like', '%' . $search . '%')
                    ->orWhere(DB::raw("CONCAT(t2.firstname,' ',t2.lastname)"), 'like', '%' . $search . '%') // customer lN
                    ->orWhere('songs.name', 'like', '%' . $search . '%')
                    ->orWhere('t1.email', 'like', '%' . $search . '%')
                    ->orWhere('reviews.created_at', 'like', '%' . $search . '%')
                    ->orWhere('reviews.ratings', 'like', '%' . $search . '%');
            });
        }

        if (isset($request->status)) {
            $filteredq = $filteredq->where('reviews.status', $request->status+1);
            $query = $query->where('reviews.status', $request->status+1);
        }
        if (isset($request->type) && $request->type!='all') {
            $filteredq = $filteredq->where('reviews.type', $request->type);
            $query = $query->where('reviews.type', $request->type);
        }

        if (isset($request->created_by)) {
            $filteredq = $filteredq->where('reviews.artist_id', $request->created_by);
            $query = $query->where('reviews.artist_id', $request->created_by);
        }

        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date($request->startDate);
            $endDate = date($request->endDate);
            $filteredq = $filteredq->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(reviews.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
            $query = $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(reviews.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
        }

        $filteredq = $filteredq->selectRaw('count(*) as total')->first();
        $totalfiltered = $filteredq->total;
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $data = [];
        foreach ($query as $key => $value) {
            $action = '';
            $isApprove = '';
            $stausAction = '';
            $editUrl = route('forumComments', $value->id);

            if ($value->status == 1){$isApprove = 'Pending';}
            else if($value->status == 2) {$isApprove = '<span style="color: #0ba360">Approved</span>';}
            else if($value->status == 3) {$isApprove = '<span style="color: red">Rejected</span>';}
            if ($isApproveDisaprove) {
                if ($value->status=='0' || $value->status=='1') {
                    $stausAction .= '<li class="nav-item">'
                        . '<a href="javascript:void(0)" data-id="' . $value->id . '" data-status="1" class="nav-link active-inactive-link" >Mark as Approved</a>'
                        . '</li>';
                }
                if ($value->status=='0' || $value->status=='2') {
                    $stausAction .= '<li class="nav-item">'
                        . '<a href="javascript:void(0)" data-id="' . $value->id . '" data-status="2" class="nav-link active-inactive-link" >Mark as Rejected</a>'
                        . '</li>';
                }
                if ($value->status=='0' || $value->status=='3') {
                    $stausAction .= '<li class="nav-item">'
                        . '<a href="javascript:void(0)" data-id="' . $value->id . '" data-status="1" class="nav-link active-inactive-link" >Mark as Approved</a>'
                        . '</li>';
                }
            }

            $delete = ($isDeletable) ? '<li class="nav-item">'
                . '<a class="nav-link forum_delete" data-id="' . $value->id . '">Delete</a>'
                . '</li>' : '';
            if ($stausAction || $delete) {
                $action .= '<div class="d-inline-block dropdown">'
                    . '<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                    . '<span class="btn-icon-wrapper pr-2 opacity-7">'
                    . '<i class="fa fa-cog fa-w-20"></i>'
                    . '</span>'
                    . '</button>'
                    . '<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                    . '<ul class="nav flex-column">'
                    . $stausAction . $delete
                    . '</ul>'
                    . '</div>'
                    . '</div>';
            }
            $icon = '<img width="50" height="50" src=' . Songs::getIcon($value->icon) . '>';
            switch ($value->type) {
              case 'song':
              $type='Song';
              $SongArtistName=$value->songName;
              break;
              case 'artist':
              $type='Artist';
              $SongArtistName=$value->ArtistFirstName.' '.$value->ArtistLastName;
              break;

              default:
                // code...
                break;
            }
            $CustomerName = $value->customerFirstName.' '.$value->customerLastName;
            $classRow = $value->status == 3?"row_unapproved":"";
            $data[] = [
                $classRow,
                $action,
                $icon,
                $SongArtistName,
                $type,
                $CustomerName,
                $value->email,
                $value->ratings,
                Str::limit($value->reviews, 50),
                $isApprove,
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




    public function activeInactive(Request $request)
    {
        try {
            $model = Reviews::where('id', $request->forum_id)->first();

            if ($request->status == 1) {
                $model->status = $request->status+1;
                $msg = "Review & Rating Approved Successfully!";
            } else if ($request->status == 2) {
                $model->status = $request->status+1;
                $msg = "Review & Rating Rejected Successfully";
            }
            else
            {
                $model->status = $request->status;
            }
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }

    public function delete(Request $request)
    {
        $model = Reviews::where('id', $request->forum_id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Review & Rating Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }


}
