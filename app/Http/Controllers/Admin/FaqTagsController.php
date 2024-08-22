<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqTags;
use App\Models\HomePageBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MusicGenres;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Image;
use File;

class FaqTagsController extends Controller
{
    public function index()
    {
        return view("admin.faq_tags.index");
    }
    public function list(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_faq_tags_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_faq_tags_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        //$orderby = ['id', 'name', 'status', 'sort_order'];


        $total = FaqTags::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = FaqTags::whereNull('deleted_at');

        $filteredq = FaqTags::whereNull('deleted_at');
        $totalfiltered = $total->total;
        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('tag_name', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('tag_name', 'like', '%' . $search . '%');
            });
            $filteredq = $filteredq->selectRaw('count(*) as total')->first();
            $totalfiltered = $filteredq->total;
        }
        $query = $query->/*orderBy($orderby[$column], $order)->offset($start)->limit($length)->*/get();
        $data = [];
        foreach ($query as $key => $value) {
            $isActive = '';
            $action = '';
            $editUrl = route('editFaqTags', $value->id);
            if ($value->status == 1) {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }

            $activeInactive = ($isEditable)?'<li class="nav-item">'
                .'<a class="nav-link active-inactive-link" >Mark as '.(( $value->status == '1')?'Inactive':'Active').'</a>'
                .'</li>':'';
            $edit = ($isEditable)?'<li class="nav-item">'
            .'<a class="nav-link" href="' . $editUrl . '" >Edit</a>'
            .'</li>':'';
            $delete = ($isDeletable)?'<li class="nav-item">'
                .'<a class="nav-link music_genres_delete" data-id="' . $value->id . '">Delete</a>'
                .'</li>':'';
            if ($activeInactive || $delete) {
                $action .= '<div class="d-inline-block dropdown">'
                    .'<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                    .'<span class="btn-icon-wrapper pr-2 opacity-7">'
                    .'<i class="fa fa-cog fa-w-20"></i>'
                    .'</span>'
                    .'</button>'
                    .'<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                    .'<ul class="nav flex-column">'
                   .$edit.$activeInactive.$delete
                    .'</ul>'
                    .'</div>'
                    .'</div>';
            }
            $image = '<img width="50" height="50" src=' . $value->image . '/>';
            $classRow = $value->status?"":"row_inactive";
            $data[] = [$classRow, $action,$value->tag_name, $image,$isActive,getFormatedDate($value->created_at), $action];
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
        $model = new FaqTags;
        return view('admin.faq_tags.form', compact('model'));
    }
    public function store(Request $request)
    {
            $gen = new FaqTags();
            $gen->tag_name = $request->name;
            $gen->status = $request->is_active;
            $gen->save();
            $notification = array(
                'message' => 'Faq Tag Added successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/faq-tags/index')->with($notification);
    }

    public function edit($id)
    {
        $model = FaqTags::findOrFail($id);
        return view('admin.faq_tags.form', compact('model'));
    }

    public function update(Request $request, $id)
    {

        $home = FaqTags::findOrFail($id);
        if (!empty($home)) {
            $home->tag_name = $request->name;
            $home->status = $request->is_active;
            $home->save();
            $notification = array(
                'message' => 'Faq Tag updated successfully!',
                'alert-type' => 'success'
            );

            return redirect(config('app.adminPrefix').'/faq-tags/index')->with($notification);
        }

    }

    public function activeInactive(Request $request)
    {
        try {
            $model = FaqTags::where('id', $request->h_id)->first();
            if ($request->status == 1) {
                $model->status = $request->status;
                $msg = "FAQ Tags Activated Successfully!";
            } else {
                $model->status = $request->status;
                $msg = "FAQ Tags Deactivated Successfully!";
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
        $model = FaqTags::where('id', $request->h_id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Faq Tag Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
}
