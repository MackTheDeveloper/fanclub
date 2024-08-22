<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\FooterLink;
use App\Models\CmsPages;
use App\Models\MusicGenres;
use App\Models\Artist;
use App\Models\MusicLanguages;
use App\Models\MusicCategories;
use App\Models\GlobalLanguage;
use App\Models\DynamicGroups;
use Auth;
use Validator;
use File;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;

class FooterNewController extends Controller
{
    public function index()
    {
        return view("admin.footerNew.index");
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
        //$orderby = ['','', 'name','type', 'sort_order','', ''];


        $total = FooterLink::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = FooterLink::select('id','name','type','sort_order','is_active')->whereNull('deleted_at');
        $filteredq = FooterLink::whereNull('deleted_at');
        $totalfiltered = $total->total;
        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%')
                ->orWhere('type', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%')
                ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        $filteredq = $filteredq->selectRaw('count(*) as total')->first();
        $totalfiltered = $filteredq->total;
        $query = $query->get();
        $data = [];
        foreach ($query as $key => $value) {
            $action = '';
            $isActive = '';
            $editUrl = route('editFooter', $value->id);
            if ($value->is_active == 1) {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }
            $subaction = ($isEditable)?'<li class="nav-item">'
                        .'<a class="nav-link" href="' . $editUrl . '">Edit</a>'
                    .'</li>':'';
            $subaction .= ($isEditable)?'<li class="nav-item">'
            .'<a class="nav-link active-inactive-link" data-id="' . $value->id . '" >Mark as '.(( $value->is_active == '1')?'Inactive':'Active').'</a>'
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

            $classRow = $value->is_active?"":"row_inactive";
            $data[] = [$classRow,$action,ucfirst($value->name),ucfirst($value->type),$value->sort_order];
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
        $type_ids = array();
        $model = new FooterLink();
        $model->sort_order = FooterLink::getSortOrder();
        $page_name = 'create';
        $types = FooterLink::getTypes();
        return view('admin.footerNew.form', compact('model', 'types'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        // pre($input);
        $validator = Validator::make(
            $input,
            [
                'type' => 'required',
                'name' => 'required',
                'dropdown' => 'required',
                'sort_order' => 'required',
                'is_active' => 'required',
            ]
        );

        if ($validator->fails())
        {
            $notification = array(
                'message' => 'Please fill all required fields',
                'alert-type' => 'error'
            );
            return redirect(config('app.adminPrefix').'/footer-link/create')->with($notification);
        }
        else
        {
            try {
                $cmsPage = FooterLink::firstOrCreate(array('type' =>$request->type));
                $cmsPage->name = $request->name;
                $cmsPage->type = $request->type;
                $cmsPage->relation_data = implode(",",$request->dropdown);
                $cmsPage->sort_order = $request->sort_order;
                $cmsPage->is_active = $request->is_active;
                $cmsPage->deleted_at = NULL;
                $cmsPage->save();
                $notification = array(
                    'message' => 'Footer added successfully!',
                    'alert-type' => 'success'
                );
                return redirect(config('app.adminPrefix').'/footer-link/index')->with($notification);
            } catch (\Exception $e) {
                // Session::flash('error', $e->getMessage());
                $notification = array(
                    'message' => $e->getMessage(),
                    'alert-type' => 'error'
                );
                return redirect(config('app.adminPrefix').'/footer-link/create')->with($notification);
                // return redirect(config('app.adminPrefix').'/footer/list');
            }
        }
    }

    public function edit($id)
    {
        $model = FooterLink::findOrFail($id);
        $type = $model->type;
        $type_id = $model->relation_data;
        $id = $model->id;
        $model->sort_order = FooterLink::getSortOrder();
        $page_name = 'edit';
        $types = FooterLink::getTypes();
        return view('admin.footerNew.form', compact('model', 'id','page_name','type','type_id', 'types'));
    }

    public function update(Request $request, $id)
    {
        $model = FooterLink::findOrFail($id);
        $model->name = $request->name;
        $model->sort_order = $request->sort_order;
        $model->is_active = $request->is_active;
        $model->type = $request->type;
        $model->relation_data = implode(",",$request->dropdown);
        $model->save();

            $notification = array(
                'message' => 'Footer updated successfully!',
                'alert-type' => 'success'
            );
        return redirect(config('app.adminPrefix').'/footer-link/index')->with($notification);
    }

    public function activeInactive(Request $request)
    {
        try {
            $model = FooterLink::where('id', $request->how_it_works_id)->first();
            if ($model->is_active == 0) {
                $model->is_active = 1;
                $msg = "Footer Activated Successfully!";
            } else {
                $model->is_active = 0;
                $msg = "Footer Deactivated Successfully!";
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
        $model = FooterLink::where('id', $request->how_it_works_id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Footer Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }

    public function getSortOrder($type){
        return FooterLink::getSortOrder($type);
    }
    public function getType(Request $request)
    {
        $data = $ids = [];
        $footerLink = FooterLink::where('type',$request->type)->first();
        if ($footerLink) {
            $ids = explode(',',$footerLink->relation_data);
        }
        if ($request->type == 'cms')
        {
            $data = CmsPages::select('id', 'name')->whereNull('deleted_at')->get();
        }
        else if ($request->type == 'category')
        {
            $data = MusicCategories::select('id', 'name')->whereNull('deleted_at')->get();
        }
        else if ($request->type == 'language')
        {
            $data = MusicLanguages::select('id', 'name')->whereNull('deleted_at')->get();
        }
        else if ($request->type == 'genre')
        {
            $data = MusicGenres::select('id', 'name')->whereNull('deleted_at')->get();
        }
        else if ($request->type == 'artist')
        {
            $data = Artist::select('id', 'firstname as name')->whereNull('deleted_at')->where('role_id',2)->get();
        }
        else if ($request->type == 'dynamicgroup')
        {
            $data = DynamicGroups::select('id', 'name')->where('status','1')->whereNull('deleted_at')->get();
        }
        return Response::json(['data'=>$data,'selected'=>$ids]);
    }
}
