<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmojisAndComments;
use App\Models\HowItWorksApp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\HowItWorks;
use App\Models\GlobalLanguage;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;

class EmojisAndCommentsController extends Controller
{
    public function index()
    {
        return view("admin.emojis_and_comments.index");
    }
    public function list(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_emojis_comments_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_emojis_comments_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['id', 'type','image', 'comment','status', 'sort_order'];


        $total = EmojisAndComments::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = EmojisAndComments::whereNull('deleted_at');
        $filteredq = EmojisAndComments::whereNull('deleted_at');
        $totalfiltered = $total->total;

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('type', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('type', 'like', '%' . $search . '%');
            });
        }

        if (isset($request->type) && $request->type!='all') {
            $filteredq = $filteredq->where('type', $request->type);
            $query = $query->where('type', $request->type);
        }

        $filteredq = $filteredq->selectRaw('count(*) as total')->first();
        $totalfiltered = $filteredq->total;
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $data = [];
        foreach ($query as $key => $value) {
            $action = '';
            $isActive = '';
            if ($value->status == 1) {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }
            $editUrl = route('emojiEdits', $value->id);
            $subaction = ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link" href="' . $editUrl . '" title="edit">Edit</a>'
                . '</li>' : '';
            $subaction .= ($isDeletable)?'<li class="nav-item">'
            .'<a class="nav-link how_it_works_delete" data-id="' . $value->id . '">Delete</a>'
        .'</li>':'';
            $activeInactive = ($isEditable)?'<li class="nav-item">'
                .'<a class="nav-link active-inactive-link" >Mark as '.(( $value->status == '1')?'Inactive':'Active').'</a>'
            .'</li>':'';
            if ($activeInactive) {
                $action .= '<div class="d-inline-block dropdown">'
                    .'<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                        .'<span class="btn-icon-wrapper pr-2 opacity-7">'
                            .'<i class="fa fa-cog fa-w-20"></i>'
                        .'</span>'
                    .'</button>'
                    .'<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                        .'<ul class="nav flex-column">'
                        .$subaction.$activeInactive
                        .'</ul>'
                    .'</div>'
                .'</div>';
            }
            $comment = isset($value->comment) ? $value->comment : '';
            $image =   $value->type == 'icon' ? '<img width="50" height="50" src=' . $value->image . '/>' : '';
            $sortorder = isset($value->sort_order) ? $value->sort_order : '';
            $classRow = $value->status?"":"row_inactive";
            $data[] = [$classRow,$action, ucfirst($value->type),$image,Str::limit($comment, 100),$isActive,$sortorder];
        }
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }


    public function add()
    {
        $model = new EmojisAndComments;
        $model->sort_order = EmojisAndComments::getSortOrder();
        return view('admin.emojis_and_comments.form', compact('model'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        if ($request->type == 'icon')
        {
            $validator = \Illuminate\Support\Facades\Validator::
            make($input,['image' => 'required|mimes:jpeg,jpg,png,gif',]);
        }
        else if($request->type == 'comment')
        {
            $validator = \Illuminate\Support\Facades\Validator::
            make($input,['comment' => 'required',]
            );
        }

        if ($validator->fails())
        {
            $notification = array(
                'message' => 'Emojis and Comment Validation Required!',
                'alert-type' => 'error'
            );
            return redirect(config('app.adminPrefix').'/emojis-and-comments/index')->with($notification);
        }
        else {
            try {
                $input = $request->all();
                $emo = new EmojisAndComments();
                $emo->sort_order = $request->sort_order;
                $emo->type = $request->type;
                $emo->status = $request->is_active;
                if ($request->type == 'icon') {
                    if ($request->hasFile('image')) {
                        $fileObject = $request->file('image');
                        $image = EmojisAndComments::uploadAndSaveImage($fileObject);
                        $emo['image'] = $image;
                    } else {
                        unlink($emo['image']);
                    }
                } else if ($request->type == 'comment') {
                    $emo->comment = $request->comment;
                }

                $emo->save();
                $notification = array(
                    'message' => 'Emojis and Comment added successfully!',
                    'alert-type' => 'success'
                );
                return redirect(config('app.adminPrefix').'/emojis-and-comments/index')->with($notification);
            } catch (\Exception $e) {
                pre($e->getMessage());
                return redirect(config('app.adminPrefix').'/emojis-and-comments/index');
            }
        }
    }

    public function edit($id)
    {
        $model = EmojisAndComments::findOrFail($id);
        return view('admin.emojis_and_comments.form', compact('model'));
    }

    public function update(Request $request, $id)
    {
        // $input = $request->all();
        // if ($request->type == 'icon')
        // {
        //     $validator = \Illuminate\Support\Facades\Validator::
        //     make($input,['image' => 'required|mimes:jpeg,jpg,png,gif',]);
        // }
        // else if($request->type == 'comment')
        // {
        //     $validator = \Illuminate\Support\Facades\Validator::
        //     make($input,['comment' => 'required',]
        //     );
        // }
        // if ($validator->fails())
        // {
        //     $notification = array(
        //         'message' => 'Emojis and Comment Validation Required!',
        //         'alert-type' => 'danger'
        //     );
        //     return redirect(config('app.adminPrefix').'/emojis-and-comments/index')->with($notification);
        // }
            try {
                $model = EmojisAndComments::findOrFail($id);
                $model->sort_order = $request->sort_order;
                $model->type = $request->type;
                $model->status = $request->is_active;
                if ($request->type == 'icon') {
                    if ($request->hasFile('image')) {
                        $fileObject = $request->file('image');
                        $image = EmojisAndComments::uploadAndSaveImage($fileObject);
                        $model['image'] = $image;

                    }
                    EmojisAndComments::where('id', $request->id)->update(['comment' => NULL]);
                } else if ($request->type == 'comment') {
                    $model->comment = $request->comment;
                    EmojisAndComments::where('id', $request->id)->update(['image' => NULL]);
                }

                $model->save();
                $notification = array(
                    'message' => 'Emojis and Comment updated successfully!',
                    'alert-type' => 'success'
                );
                return redirect(config('app.adminPrefix').'/emojis-and-comments/index')->with($notification);
            } catch (\Exception $e) {
                dd($e->getMessage());
                pre($e->getMessage());
                return redirect(config('app.adminPrefix').'/emojis-and-comments/index');
            }

    }

    public function activeInactive(Request $request)
    {

        try {
            $model = EmojisAndComments::where('id', $request->id)->first();
            if ($request->status == 1) {
                $model->status = $request->status;
                $msg = "Emojis and Comment Activated Successfully!";
            } else {
                $model->status = $request->status;
                $msg = "Emojis and Comment Deactivated Successfully!";
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
        $model = EmojisAndComments::where('id', $request->id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Emojis and Comment Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
}
