<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MusicCategories;
use App\Models\GlobalLanguage;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response; 
use DB;
use Image;


class MusicCategoriesController extends Controller
{
    public function index()
    {
        return view("admin.music_categories.index");
    }

    public function list(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_music_categories_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_music_categories_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['id', '', 'name','','','sort_order'];
        $total = MusicCategories::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = MusicCategories::whereNull('deleted_at');
        $filteredq = MusicCategories::whereNull('deleted_at');
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
            $isActive = '';
            $action = '';
            $editUrl = route('editMusicCategory', $value->id);
            if ($value->status == 1) {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }
            $edit = '<li class="nav-item">'
            .'<a class="nav-link" href="' . $editUrl . '">Edit</a>'
        .'</li>';
            $activeInactive = ($isEditable)?'<li class="nav-item">'
                .'<a class="nav-link active-inactive-link" >Mark as '.(( $value->status == '1')?'Inactive':'Active').'</a>'
            .'</li>':'';

            $delete = ($isDeletable)?'<li class="nav-item">'
                        .'<a class="nav-link music_categories_delete" data-id="' . $value->id . '">Delete</a>'
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
            $image = '<img height="50" src=' . $value->image . '/>';
            $classRow = $value->status?"":"row_inactive";
            $data[] = [$classRow,$action,$value->name,$image, $isActive,$value->sort_order];
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
        $model = new MusicCategories;
        $model->sort_order = MusicCategories::getSortOrder();
        return view('admin.music_categories.form', compact('model'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'name' => 'required',
                'sortOrder' => 'required',
                'image' => 'required|mimes:jpeg,jpg,png,gif',
            ]
        );

        if ($validator->fails())
        {
            $notification = array(
                'message' => 'Validation Required!',
                'alert-type' => 'error'
            );
            return redirect(config('app.adminPrefix').'/music-categories/index')->with($notification);

        }
        else
        {
            $cat = new MusicCategories();
            $cat->name = $request->name;
            $cat->sort_order = $request->sortOrder;
            if ($request->hasFile('image')) {
                $fileObject = $request->file('image');
                $image = MusicCategories::uploadAndSaveImage($fileObject);
                // $input['image'] = $image;
                $cat->image = $image;
            }else{
                if (isset($input['image'])) {
                    unlink($input['image']);
                }
            }
            // if ($request->hasFile('image')) {
            //     $photo = $request->file('image');
            //     $ext = $photo->extension();
            //     $filename = rand().'_'.time().'.'.$ext;
            //     $filePath = public_path().'/admin/music_category/';
            //     $img = Image::make($photo->path());
            //     $width = config('app.musicCategoryIconDimension.width');
            //     $height = config('app.musicCategoryIconDimension.height');
            //     if($img->width() == $width && $img->height() == $height){
            //         $photo->move($filePath.'/', $filename);
            //     }else{
            //         $img->resize($width, $height)->save($filePath.'/'.$filename);
            //     }
            //     $cat->image = $filename;
            // }
            $cat->status = $request->is_active;
            $cat->seo_title = $request->seo_title;
            $cat->seo_meta_keyword = $request->seo_meta_keyword;
            $cat->seo_description = $request->seo_description;
            $cat->save();

            $notification = array(
                'message' => 'Music Category added successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/music-categories/index')->with($notification);
        }
    }

    public function edit($id)
    {
        $model = MusicCategories::findOrFail($id);
        return view('admin.music_categories.form', compact('model'));
    }

    public function update(Request $request, $id)
    {
       /* try {
            $model = MusicCategories::findOrFail($id);
            $input = $request->all();
            $model->update($input);
            $notification = array(
                'message' => 'Music category updated successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/music-categories/index')->with($notification);
        } catch (\Exception $e) {
            return redirect(config('app.adminPrefix').'/music-categories/index');
        }*/
        $home = MusicCategories::findOrFail($id);
        if (!empty($home)) {
            $home->name = $request->name;
            if ($request->hasFile('image')) {
                $fileObject = $request->file('image');
                $image = MusicCategories::uploadAndSaveImage($fileObject,$id);
                // $input['image'] = $image;
                $home->image = $image;
            }else{
                if (isset($input['image'])) {
                    unlink($input['image']);
                }
            }
            // if ($request->hasFile('image')) {
            //     $photo = $request->file('image');
            //     $ext = $photo->extension();
            //     $filename = rand().'_'.time().'.'.$ext;
            //     $filePath = public_path().'/admin/music_category/';
            //     $img = Image::make($photo->path());
            //     $width = config('app.musicCategoryIconDimension.width');
            //     $height = config('app.musicCategoryIconDimension.height');
            //     if($img->width() == $width && $img->height() == $height){
            //         $photo->move($filePath.'/', $filename);
            //     }else{
            //         $img->resize($width, $height)->save($filePath.'/'.$filename);
            //     }
            //     $home->image = $filename;
            // }
            $home->sort_order = $request->sortOrder;
            $home->status = $request->is_active;
            $home->seo_title = $request->seo_title;
            $home->seo_meta_keyword = $request->seo_meta_keyword;
            $home->seo_description = $request->seo_description;
            $home->save();

            $notification = array(
                'message' => 'Music Category updated successfully!',
                'alert-type' => 'success'
            );

            return redirect(config('app.adminPrefix').'/music-categories/index')->with($notification);
        }
    }

    public function activeInactive(Request $request)
    {
        try {
            $model = MusicCategories::where('id', $request->music_categories_id)->first();
            if ($request->status == 1) {
                $model->status = $request->status;
                $msg = "Music category Activated Successfully!";
            } else {
                $model->status = $request->status;
                $msg = "Music category Deactivated Successfully!";
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
        $model = MusicCategories::where('id', $request->music_categories_id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Music category Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
}
