<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
use Image;
class HowItWorksAppController extends Controller
{
    public function index()
    {
        return view("admin.how_it_works_app.index");
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
        $orderby = ['id','title','type','description',''];


        $total = HowItWorksApp::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = HowItWorksApp::whereNull('deleted_at');
        $filteredq = HowItWorksApp::whereNull('deleted_at');
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
            $isActive = '';
            if ($value->status == 1) {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }
            $editUrl = route('editHowItWorksApp', $value->id);
            $subaction = ($isEditable)?'<li class="nav-item">'
                        .'<a class="nav-link" href="' . $editUrl . '">Edit</a>'
                    .'</li>':'';
            $subaction .= ($isEditable)?'<li class="nav-item">'
                .'<a class="nav-link active-inactive-link" >Mark as '.(( $value->status == '1')?'Inactive':'Active').'</a>'
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
            $classRow = $value->status?"":"row_inactive";
            $data[] = [$classRow,$action,$value->title,ucfirst($value->type),$value->description,$image,$isActive];
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
        $model = new HowItWorksApp;
        return view('admin.how_it_works_app.form', compact('model'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                // 'image' => 'required',
                'image' => 'required|mimes:jpeg,jpg,png,gif',
            ]
        );

        if ($validator->fails())
        {
            $notification = array(
                'message' => 'Validation Required!',
                'alert-type' => 'error'
            );
            return redirect(config('app.adminPrefix').'/how-it-works-app/index')->with($notification);

        }
        else
        {
            $homepage = new HowItWorksApp();
            $homepage->type = $request->type;
            if ($request->hasFile('image')) {
                $fileObject = $request->file('image');
                $image = HowItWorksApp::uploadAndSaveImage($fileObject);
                $homepage->image = $image;
            }
            // if ($request->hasFile('image')) {
            //     $photo = $request->file('image');
            //     $ext = $photo->extension();
            //     $filename = rand().'_'.time().'.'.$ext;
            //     $filePath = public_path().'/admin/how_it_works_app';
            //     $img = Image::make($photo->path());
            //     $width = config('app.homePageImageHeight.width');
            //     $height = config('app.homePageImageHeight.height');
            //     if($img->width() == $width && $img->height() == $height){
            //         $photo->move($filePath.'/', $filename);
            //     }else{
            //         $img->resize($width, $height)->save($filePath.'/'.$filename);
            //     }
            //     $homepage->image = $filename;
            // }
            if ($request->is_active=='1') {
                HowItWorksApp::where('type',$request->type)->update(['status' => 0]);
            }
            $homepage->status = $request->is_active;
            $homepage->title = $request->title;
            $homepage->description = $request->description;
            $homepage->save();

            $notification = array(
                'message' => 'How it works App added successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/how-it-works-app/index')->with($notification);
        }
    }

    public function edit($id)
    {
        $model = HowItWorksApp::findOrFail($id);
        return view('admin.how_it_works_app.form', compact('model'));
    }

    public function update(Request $request, $id)
    {
            $home = HowItWorksApp::findOrFail($id);
            if (!empty($home)) {
                $home->type = $request->type;
                if ($request->hasFile('image')) {
                    $fileObject = $request->file('image');
                    $image = HowItWorksApp::uploadAndSaveImage($fileObject,$id);
                    $home->image = $image;
                }
                if ($request->is_active=='1') {
                    HowItWorksApp::where('type',$request->type)->update(['status' => 0]);
                }
                $home->status = $request->is_active;
                $home->title = $request->title;
                $home->description = $request->description;
                $home->save();

                $notification = array(
                    'message' => 'How It Works App updated successfully!',
                    'alert-type' => 'success'
                );

                return redirect(config('app.adminPrefix').'/how-it-works-app/index')->with($notification);
            }

    }

    public function activeInactive(Request $request)
    {
        try {
            $model = HowItWorksApp::where('id', $request->how_it_works_id)->first();
            if ($request->status == 1) {
                $model->status = $request->status;
                HowItWorksApp::where('type',$model->type)->update(['status'=>0]);
                $msg = "How It Works App Activated Successfully!";
            } else {
                $model->status = $request->status;
                $msg = "How It Works App Deactivated Successfully!";
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
        $model = HowItWorksApp::where('id', $request->id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "How It Works Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
}
