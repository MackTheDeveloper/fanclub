<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Forums;
use App\Models\User;
use App\Models\ForumComments;
use App\Models\GlobalLanguage;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;

class ForumsController extends Controller
{
    public function index(Request $request)
    {
        $req = $request->all();
        $users = Forums::getListUsers();
        $search = (isset($req['search']) ? $req['search'] : '');
        return view("admin.forums.index",compact('users','search'));
    }

    public function list(Request $request)
    {
        $isCommentList = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_forum_comments_list');
        $isApproveDisaprove = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_forum_status_list');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_forum_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['id', 'post_topic','users.firstname', 'forums.created_at','forums.status', 'no_likes', 'no_comments'];


        $total = Forums::selectRaw('count(*) as total')->whereNull('forums.deleted_at')->leftjoin('users','users.id','forums.created_by')->whereNull('users.deleted_at')->first();

        $commentSelect = DB::raw("(SELECT count(*) FROM forum_comments WHERE forums.id = forum_comments.forum_id and forum_comments.deleted_at IS NULL) as no_comments");
        $query = Forums::selectRaw('forums.*,users.firstname,users.lastname,'.$commentSelect)
            ->leftjoin('users','users.id','forums.created_by')->whereNull('users.deleted_at');
        $filteredq = Forums::leftjoin('users','users.id','forums.created_by')->whereNull('users.deleted_at');
        $totalfiltered = $total->total;

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('post_topic', 'like', '%' . $search . '%')
                    ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('post_topic', 'like', '%' . $search . '%')
                    ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%');
            });
        }

        if (isset($request->status)) {
            $filteredq = $filteredq->where('status', $request->status);
            $query = $query->where('status', $request->status);
        }

        if (isset($request->created_by)) {
            $filteredq = $filteredq->where('created_by', $request->created_by);
            $query = $query->where('created_by', $request->created_by);
        }

        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date($request->startDate);
            $endDate = date($request->endDate);
            $filteredq = $filteredq->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(forums.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
            $query = $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(forums.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
        }

        $filteredq = $filteredq->selectRaw('count(*) as total')->first();
        $totalfiltered = $filteredq->total;
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        // pre($query)
        $data = [];
        foreach ($query as $key => $value) {
            $action = '';
            $isApprove = '';
            $stausAction = '';
            $editUrl = route('forumComments', $value->id);

            $isApprove = ($value->status)?(($value->status=='1')?'Approved':'Rejected'):'Pending';

            if ($isApproveDisaprove) {
                if ($value->status=='0' || $value->status=='2') {
                    $stausAction .= '<li class="nav-item">'
                        . '<a href="javascript:void(0)" data-id="' . $value->id . '" data-status="1" class="nav-link active-inactive-link" >Mark as Approved</a>'
                        . '</li>';
                }
                if ($value->status=='0' || $value->status=='1') {
                    $stausAction .= '<li class="nav-item">'
                        . '<a href="javascript:void(0)" data-id="' . $value->id . '" data-status="2" class="nav-link active-inactive-link" >Mark as Rejected</a>'
                        . '</li>';
                }
            }
            $comment = ($isDeletable) ? '<li class="nav-item">'
            . '<a class="nav-link" href="' . $editUrl . '"  >Comments</a>'
            . '</li>' : '';
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
                    .$comment . $stausAction . $delete
                    . '</ul>'
                    . '</div>'
                    . '</div>';
            }
            $classRow = $value->status == 2?"row_inactive":"";
            $data[] = [
                $classRow,
                $action,
                $value->post_topic,
                $value->firstname.' '.$value->lastname,
                // $value->created_by_name,
                getFormatedDate($value->created_at),
                $isApprove,
                $value->no_likes,
                $value->no_comments,

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



    // public function edit($id)
    // {
    //     $model = Forums::findOrFail($id);
    //     return view('admin.forums.form', compact('model'));
    // }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         $model = Forums::findOrFail($id);
    //         $input = $request->all();
    //         if ($request->hasFile('image')) {
    //             $fileObject = $request->file('image');
    //             $image = Forums::uploadAndSaveImage($fileObject);
    //             $model->image = $image;
    //         }else{
    //             if (isset($input['image'])) {
    //                 unlink($input['image']);
    //             }
    //         }
    //         $model->update($input);
    //         $notification = array(
    //             'message' => 'forum updated successfully!',
    //             'alert-type' => 'success'
    //         );
    //         return redirect(config('app.adminPrefix').'/how-it-works/index')->with($notification);
    //     } catch (\Exception $e) {
    //         pre($e->getMessage());
    //         return redirect(config('app.adminPrefix').'/how-it-works/index');
    //     }
    // }

    public function activeInactive(Request $request)
    {
        try {
            $model = Forums::where('id', $request->forum_id)->first();
            if ($request->status == 1) {
                $model->status = $request->status;
                $msg = "forum Approved Successfully!";
            } else {
                $model->status = $request->status;
                $msg = "forum Rejected Successfully!";
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
        $model = Forums::where('id', $request->forum_id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "forum Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }


    public function commentindex($id)
    {
        $model = Forums::findOrFail($id);
        return view("admin.forums.commentindex",compact('model'));
    }

    public function commentlist(Request $request,$id)
    {
        $isCommentList = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_forum_comments_listing');
        // $isApproveDisaprove = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_forum_status_list');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_forum_comments_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['id', 'users.firstname', 'comment', 'forum_comments.created_at','no_likes', ''];


        $total = ForumComments::where('forum_id',$id)->selectRaw('count(*) as total')->whereNull('forum_comments.deleted_at')->leftjoin('users','users.id','forum_comments.created_by')->whereNull('users.deleted_at')->first();
        $query = ForumComments::where('forum_id',$id)->selectRaw('forum_comments.*,users.firstname,users.lastname')
            ->leftjoin('users','users.id','forum_comments.created_by')->whereNull('users.deleted_at');
        $filteredq = ForumComments::where('forum_id',$id)->leftjoin('users','users.id','forum_comments.created_by')->whereNull('users.deleted_at');
        $totalfiltered = $total->total;

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('comment', 'like', '%' . $search . '%')
                    ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('comment', 'like', '%' . $search . '%')
                    ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%');
            });
        }

        if (isset($request->created_by)) {
            $filteredq = $filteredq->where('created_by', $request->created_by);
            $query = $query->where('created_by', $request->created_by);
        }

        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date($request->startDate);
            $endDate = date($request->endDate);
            $filteredq = $filteredq->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(forum_comments.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
            $query = $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(forum_comments.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
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
            // $editUrl = route('forumComments', $value->id);

            // $isApprove = ($value->status)?(($value->status=='1')?'Approved':'Rejected'):'Pending';

            // $action .= ($isCommentList) ? '<a href="' . $editUrl . '" title="edit"><i class="fa fa-comment" aria-hidden="true"></i></a> &nbsp; &nbsp;' : '';
            // if ($isApproveDisaprove) {
            //     if ($value->status=='0' || $value->status=='2') {
            //         $stausAction .= '<li class="nav-item">'
            //             . '<a href="javascript:void(0)" data-id="' . $value->id . '" data-status="1" class="nav-link active-inactive-link" >Mark as Approved</a>'
            //             . '</li>';
            //     }
            //     if ($value->status=='0' || $value->status=='1') {
            //         $stausAction .= '<li class="nav-item">'
            //             . '<a href="javascript:void(0)" data-id="' . $value->id . '" data-status="2" class="nav-link active-inactive-link" >Mark as Rejected</a>'
            //             . '</li>';
            //     }
            // }

            $delete = ($isDeletable) ? '<li class="nav-item">'
            . '<a class="nav-link forum_comment_delete" data-id="' . $value->id . '">Delete</a>'
            . '</li>' : '';
            $viewComment =  ($isDeletable) ? '<li class="nav-item">'
            . '<a class="nav-link forum_comment_view" data-id="' . $value->id . '">View</a>'
            . '</li>' : '';
            // $delete = ($isDeletable) ? '<li class="nav-item">'
            //     . '<a class="nav-link forum_comment_delete" data-id="' . $value->id . '">Delete</a>'
            //     . '</li>' : '';
            if ( $delete) {
                $action .= '<div class="d-inline-block dropdown">'
                    . '<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                    . '<span class="btn-icon-wrapper pr-2 opacity-7">'
                    . '<i class="fa fa-cog fa-w-20"></i>'
                    . '</span>'
                    . '</button>'
                    . '<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                    . '<ul class="nav flex-column">'
                    .  $delete .$viewComment
                    . '</ul>'
                    . '</div>'
                    . '</div>';
            }
            $classRow = $value->is_verify?($value->status?"":"row_inactive"):"row_unapproved";
            $data[] = [
                $classRow,
                $action,
                $value->firstname.' '.$value->lastname,
                Str::limit($value->comment, 100),
                getFormatedDate($value->created_at),
                $value->no_likes,
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

    public function commentdelete(Request $request)
    {
        $model = ForumComments::where('id', $request->forum_comment_id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "forum Comment Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
    public function commentView(Request $request)
    {
        $model = ForumComments::where('id', $request->id)->first();
        $author = User::where('id',$model->created_by)->first();
        $result['status'] = 'true';
        $result['comment'] = $model->comment;
        $result['name'] = $author->firstname;
        return $result;
    }
}
