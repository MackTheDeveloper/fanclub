<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\HowItWorks;
use App\Models\GlobalLanguage;
use Auth;
use Validator;
use File;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;

class HowItWorksController extends Controller
{
    public function index()
    {
        return view("admin.how_it_works.index");
    }

    public function list(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_how_it_works_app_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_how_it_works_app_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['','', 'type','', 'title','', 'sort_order'];


        $total = HowItWorks::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = HowItWorks::whereNull('deleted_at');
        $filteredq = HowItWorks::whereNull('deleted_at');
        $totalfiltered = $total->total;

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('title', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('title', 'like', '%' . $search . '%');
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
            $editUrl = route('editHowItWorks', $value->id);
            $subaction = ($isEditable)?'<li class="nav-item">'
                        .'<a class="nav-link" href="' . $editUrl . '">Edit</a>'
                    .'</li>':'';
            $subaction .=  ($isDeletable)?'<li class="nav-item">'
            .'<a class="nav-link how_it_works_delete" data-id="' . $value->id . '">Delete</a>'
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
            
            $image = '<img width="50" height="50" src=' . $value->image . '/>';
            $description = strlen($value->description) > 50 ? substr($value->description,0,50)."..." : $value->description;
            $classRow = "";
            $data[] = [$classRow,$action,ucfirst($value->type),$image,$value->title,$description,$value->sort_order];
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
        $model = new HowItWorks;
        // $model->sort_order = HowItWorks::getSortOrder();
        return view('admin.how_it_works.form', compact('model'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            // pre($input);
            if ($request->hasFile('image')) {
                $fileObject = $request->file('image');
                $image = HowItWorks::uploadAndSaveImage($fileObject);
                $input['image'] = $image;
            }else{
                unlink($input['image']);
            }
            $model = HowItWorks::create($input);
            $notification = array(
                'message' => 'How It Works Web added successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/how-it-works/index')->with($notification);
        } catch (\Exception $e) {
            pre($e->getMessage());
            return redirect(config('app.adminPrefix').'/how-it-works/index');
        }
    }

    public function edit($id)
    {
        $model = HowItWorks::findOrFail($id);
        return view('admin.how_it_works.form', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        try {
            if ($request->hasFile('image')) {
                $fileObject = $request->file('image');
                $image = HowItWorks::uploadAndSaveImage($fileObject);
                $input['image'] = $image;
            }else{
                if(isset($input['image'])) {
                    unlink($input['image']);
                }   
            }
            $model = HowItWorks::findOrFail($id);            
            $model->update($input);
            $notification = array(
                'message' => 'How It Works updated successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/how-it-works/index')->with($notification);
        } catch (\Exception $e) {
            pre($e->getMessage());
            return redirect(config('app.adminPrefix').'/how-it-works/index');
        }
    }

    public function activeInactive(Request $request)
    {
        try {
            $model = HowItWorks::where('id', $request->how_it_works_id)->first();
            if ($request->status == 1) {
                $model->status = $request->status;
                $msg = "How It Works Web Activated Successfully!";
            } else {
                $model->status = $request->status;
                $msg = "How It Works Web Deactivated Successfully!";
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
        $model = HowItWorks::where('id', $request->how_it_works_id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "How It Works Web Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }

    public function getSortOrder($type){
        return HowItWorks::getSortOrder($type);
    }
}
