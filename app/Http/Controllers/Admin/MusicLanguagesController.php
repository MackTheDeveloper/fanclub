<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MusicLanguages;
use App\Models\GlobalLanguage;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Image;

class MusicLanguagesController extends Controller
{
    public function index()
    {
        return view("admin.music_languages.index");
    }

    public function list(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_music_languages_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_music_languages_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['id', '', 'name','','','sort_order'];
        $total = MusicLanguages::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = MusicLanguages::whereNull('deleted_at');
        $filteredq = MusicLanguages::whereNull('deleted_at');
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
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $data = [];
        foreach ($query as $key => $value) {
            $icon = '<img width="50" height="50" src='.'src="https://localhost/clubfan/public/admin/music_genre/'. $value->image . '>';
            $isActive = '';
            $action = '';
            $editUrl = route('editMusicLanguage', $value->id);
            if ($value->status == 1) {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }

            $edit = ($isEditable)?'<li class="nav-item">'
            .'<a class="nav-link " href="' . $editUrl . '">Edit</a>'
            .'</li>':'';
            $activeInactive = ($isEditable)?'<li class="nav-item">'
                .'<a class="nav-link active-inactive-link" >Mark as '.(( $value->status == '1')?'Inactive':'Active').'</a>'
            .'</li>':'';

            $delete = ($isDeletable)?'<li class="nav-item">'
                        .'<a class="nav-link music_languages_delete" data-id="' . $value->id . '">Delete</a>'
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
            $data[] = [$classRow,$action,$value->name, $image,$isActive,$value->sort_order];
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
        $model = new MusicLanguages;
        $model->sort_order = MusicLanguages::getSortOrder();
        return view('admin.music_languages.form', compact('model'));
    }

    public function store(Request $request)
    {
//        try {
//            $input = $request->all();
//            $model = MusicLanguages::create($input);
//            $notification = array(
//                'message' => 'Music Language added successfully!',
//                'alert-type' => 'success'
//            );
//            return redirect(config('app.adminPrefix').'/music-languages/index')->with($notification);
//        } catch (\Exception $e) {
//            return redirect(config('app.adminPrefix').'/music-languages/index');
//        }
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

        if ($validator->fails())
        {
            $notification = array(
                'message' => 'Validation Required!',
                'alert-type' => 'error'
            );
            return redirect(config('app.adminPrefix').'/music-languages/index')->with($notification);

        }
        else
        {
            $homepage = new MusicLanguages();
            $homepage->name = $request->name;
            $homepage->sort_order = $request->sortOrder;
            // if ($request->hasFile('image')) {
            //     $photo = $request->file('image');
            //     $ext = $photo->extension();
            //     $filename = rand().'_'.time().'.'.$ext;
            //     $filePath = public_path().'/admin/music_language/';
            //     $img = Image::make($photo->path());
            //     $width = config('app.musicLanguageIconDimension.width');
            //     $height = config('app.musicLanguageIconDimension.height');
            //     if($img->width() == $width && $img->height() == $height){
            //         $photo->move($filePath.'/', $filename);
            //     }else{
            //         $img->resize($width, $height)->save($filePath.'/'.$filename);
            //     }
            //     $homepage->image = $filename;
            // }
            if ($request->hasFile('image')) {
                $fileObject = $request->file('image');
                $image = MusicLanguages::uploadAndSaveImage($fileObject);
                // $input['image'] = $image;
                $homepage->image = $image;
            }else{
                if (isset($input['image'])) {
                    unlink($input['image']);
                }
            }
            $homepage->status = $request->is_active;
            $homepage->save();

            $notification = array(
                'message' => 'Music Language added successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/music-languages/index')->with($notification);
        }
    }

    public function edit($id)
    {
        $model = MusicLanguages::findOrFail($id);
        return view('admin.music_languages.form', compact('model'));
    }

    public function update(Request $request, $id)
    {
//        try {
//            $model = MusicLanguages::findOrFail($id);
//            $input = $request->all();
//            $model->update($input);
//            $notification = array(
//                'message' => 'Music Language updated successfully!',
//                'alert-type' => 'success'
//            );
//            return redirect(config('app.adminPrefix').'/music-languages/index')->with($notification);
//        } catch (\Exception $e) {
//            return redirect(config('app.adminPrefix').'/music-languages/index');
//        }
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'name' => 'required',
                'sortOrder' => 'required',                
            ]
        );

        if ($validator->fails())
        {
            $notification = array(
                'message' => 'Validation Required!',
                'alert-type' => 'error'
            );
            return redirect(config('app.adminPrefix').'/music-languages/index')->with($notification);

        }
        else
        {
            $home = MusicLanguages::findOrFail($id);
            if (!empty($home)) {
                $home->name = $request->name;
                // if ($request->hasFile('image')) {
                //     $photo = $request->file('image');
                //     $ext = $photo->extension();
                //     $filename = rand().'_'.time().'.'.$ext;
                //     $filePath = public_path().'/admin/music_language/';
                //     $img = Image::make($photo->path());
                //     $width = config('app.musicLanguageIconDimension.width');
                //     $height = config('app.musicLanguageIconDimension.height');
                //     if($img->width() == $width && $img->height() == $height){
                //         $photo->move($filePath.'/', $filename);
                //     }else{
                //         $img->resize($width, $height)->save($filePath.'/'.$filename);
                //     }
                //     $home->image = $filename;
                // }
                if ($request->hasFile('image')) {
                    $fileObject = $request->file('image');
                    $image = MusicLanguages::uploadAndSaveImage($fileObject,$id);
                    // $input['image'] = $image;
                    $home->image = $image;
                }else{
                    if (isset($input['image'])) {
                        unlink($input['image']);
                    }
                }
                $home->sort_order = $request->sortOrder;
                $home->status = $request->is_active;
                $home->save();

                $notification = array(
                    'message' => 'Music Language updated successfully!',
                    'alert-type' => 'success'
                );

                return redirect(config('app.adminPrefix').'/music-languages/index')->with($notification);
            }
        }
    }

    public function activeInactive(Request $request)
    {
        try {
            $model = MusicLanguages::where('id', $request->music_languages_id)->first();
            if ($request->status == 1) {
                $model->status = $request->status;
                $msg = "Music Language Activated Successfully!";
            } else {
                $model->status = $request->status;
                $msg = "Music Language Deactivated Successfully!";
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
        $model = MusicLanguages::where('id', $request->music_languages_id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Music Language Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
}
