<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePageBanner;
use App\Models\Artist;
use App\Models\DynamicGroups;
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

class HomePageBannerController extends Controller
{
    public function index()
    {
        return view("admin.homepage_banner.index");
    }
    public function list(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_homepage_banner_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_homepage_banner_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        //$orderby = ['id', 'name', 'status', 'sort_order'];


        $total = HomePageBanner::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = HomePageBanner::whereNull('deleted_at');
        $filteredq = HomePageBanner::whereNull('deleted_at');
        $totalfiltered = $total->total;
        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
            });
            $filteredq = $filteredq->selectRaw('count(*) as total')->first();
            $totalfiltered = $filteredq->total;
        }
        $query = $query->/*orderBy($orderby[$column], $order)->offset($start)->limit($length)->*/get();
        $data = [];
        foreach ($query as $key => $value) {
            $isActive = '';
            $action = '';
            $editUrl = route('editHPB', $value->id);
            if ($value->is_active == 1) {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }

            $activeInactive = ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link active-inactive-link" >Mark as ' . (($value->is_active == '1') ? 'Inactive' : 'Active') . '</a>'
                . '</li>' : '';
            $edit = ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link" href="' . $editUrl . '" >Edit</a>'
                . '</li>' : '';
            $delete = ($isDeletable) ? '<li class="nav-item">'
                . '<a class="nav-link music_genres_delete" data-id="' . $value->id . '">Delete</a>'
                . '</li>' : '';
            if ($activeInactive || $delete) {
                $action .= '<div class="d-inline-block dropdown">'
                    . '<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                    . '<span class="btn-icon-wrapper pr-2 opacity-7">'
                    . '<i class="fa fa-cog fa-w-20"></i>'
                    . '</span>'
                    . '</button>'
                    . '<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                    . '<ul class="nav flex-column">'
                    . $edit . $activeInactive . $delete
                    . '</ul>'
                    . '</div>'
                    . '</div>';
            }
            $image = '<img width="50" height="50" src=' . $value->image . '/>';
            $classRow = $value->is_active ? "" : "row_inactive";
            $data[] = [$classRow, $action, $value->name, $image, $isActive, $value->sortOrder, $action];
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
        $model = new HomePageBanner;
        $model->sortOrder = HomePageBanner::getSortOrder();
        $types = HomePageBanner::getListType();
        return view('admin.homepage_banner.form', compact('model', 'types'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'name' => 'required',
                'sortOrder' => 'required',
                // 'image' => 'required',
                'image' => 'required|mimes:jpeg,jpg,png,gif',
            ]
        );

        if ($validator->fails()) {
            $notification = array(
                'message' => 'Validation Required!',
                'alert-type' => 'error'
            );
            return redirect(config('app.adminPrefix') . '/homepagebanner/index')->with($notification);
        } else {
            $gen = new HomePageBanner();
            $gen->name = $request->name;
            $gen->sortOrder = $request->sortOrder;
            if (!$request->type_value == 0) {
                $gen->type = $request->type_value;
                $gen->type_id = $request->type_id;
            }
            if ($request->hasFile('image')) {
                $fileObject = $request->file('image');
                $image = HomePageBanner::uploadAndSaveImage($fileObject);
                // $input['image'] = $image;
                $gen->image = $image;
            } else {
                if (isset($input['image'])) {
                    unlink($input['image']);
                }
            }
            $gen->is_active = $request->is_active;
            $gen->save();

            $notification = array(
                'message' => 'HomePage Banner Added successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix') . '/homepagebanner/index')->with($notification);
        }
    }

    public function edit($id)
    {
        $model = HomePageBanner::findOrFail($id);
        $type = $model->type;
        $type_id = $model->type_id;
        $id = $model->id;
        $page_name = 'edit';
        $types = HomePageBanner::getListType();
        return view('admin.homepage_banner.form', compact('model', 'id', 'page_name', 'type', 'type_id', 'types'));
    }

    public function update(Request $request, $id)
    {
        $home = HomePageBanner::findOrFail($id);
        if (!empty($home)) {
            $home->name = $request->name;
            if ($request->hasFile('image')) {
                $fileObject = $request->file('image');
                $image = HomePageBanner::uploadAndSaveImage($fileObject);
                // $input['image'] = $image;
                $home->image = $image;
            } else {
                if (isset($input['image'])) {
                    unlink($input['image']);
                }
            }
            if (!$request->type_value == 0 && is_numeric($request->type_id)) {
                $home->type = $request->type_value;
                $home->type_id = $request->type_id;
            }
            else
            {
                $home->type = 0;
                $home->type_id = 0;
            }
            $home->sortOrder = $request->sortOrder;
            $home->is_active = $request->is_active;
            $home->save();

            $notification = array(
                'message' => 'HomePage Banner updated successfully!',
                'alert-type' => 'success'
            );

            return redirect(config('app.adminPrefix') . '/homepagebanner/index')->with($notification);
        }
    }

    public function activeInactive(Request $request)
    {
        try {
            $model = HomePageBanner::where('id', $request->h_id)->first();
            if ($request->status == 1) {
                $model->is_active = $request->status;
                $msg = "HomePage Banner Activated Successfully!";
            } else {
                $model->is_active = $request->status;
                $msg = "HomePage Banner Deactivated Successfully!";
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
        $model = HomePageBanner::where('id', $request->h_id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "HomePage Banner Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
    public function getType(Request $request)
    {
        $data = "";
        if ($request->type == 2) {
            $data = Artist::select(DB::raw("firstname AS name"), "id")->whereNull('deleted_at')->where('role_id', 2)->get();
        }
        if ($request->type == 1) {
            $data = DynamicGroups::has('groupItem')->select('id', 'name')->where('status', '1')->whereIn('type', ['1','2'])->whereNull('deleted_at')->get();
        }
        return Response::json(['data' => $data]);
    }

    public function existType(Request $request)
    {
        $model = HomePageBanner::where('id', $request->id)->first();
        if ($request->type == 1) {
            $group = DynamicGroups::where('id', $model->type_id)->first();
            $data = $group->name;
        } else {
            $artist = Artist::where('id', $model->type_id)->first();
            $data = $artist->firstname . ' ' . $artist->lastname;
        }
        return Response::json(['data' => $data]);
    }
}
