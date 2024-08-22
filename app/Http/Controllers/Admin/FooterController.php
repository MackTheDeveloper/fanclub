<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\ArtistDetail;
use App\Models\Customer;
use App\Models\Fan;
use App\Models\FooterLink;
use App\Models\MusicCategories;
use App\Models\MusicGenres;
use App\Models\MusicLanguages;
use Carbon\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use DataTables;
use App\Models\GlobalCurrency;
use App\Models\CmsPages;
use App\Models\CurrencyConversionRate;
use Carbon\Carbon;
use DB;
use Session;
use Response;

class FooterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                DB::statement(DB::raw('set @rownum=0'));
                $footer = FooterLink::select('id','name','type','sort_order','is_active')->whereNull('deleted_at')
                ->orderBy('sort_order', 'DESC')->get();
                // dd($packages);
                return Datatables::of($footer)->make(true);
            } catch (\Exception $e) {
                return view('errors.500');  
            }
        }
        return view('admin.footer.index');
    }

    public function create(Request $request)
    {
        $type_ids = array();
        $model = new FooterLink();
        $model->sort_order = FooterLink::getSortOrder();
        $page_name = 'create';
        return view('admin.footer.form', compact('page_name', 'model','type_ids'));
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
            return redirect(config('app.adminPrefix').'/footer/create')->with($notification);
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
                return redirect(config('app.adminPrefix').'/footer/list')->with($notification);
            } catch (\Exception $e) {
                // Session::flash('error', $e->getMessage());
                $notification = array(
                    'message' => $e->getMessage(),
                    'alert-type' => 'error'
                );
                return redirect(config('app.adminPrefix').'/footer/create')->with($notification);
                // return redirect(config('app.adminPrefix').'/footer/list');
            }
        }
    }

    public function delete(Request $request,$id)
    {
        $cmsPage = FooterLink::select('id')
            ->where('id', $id)
            ->first();

        if (!empty($cmsPage)) {
            $cmsPage->deleted_at = Carbon::now();
            $cmsPage->save();
            $result['status'] = 'true';
            $result['msg'] = "Footer Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }

    public function cmsPageActiveInactive(Request $request)
    {
        try {
            $cmsPage = FooterLink::where('id', $request->cms_page_id)->first();
            if ($request->is_active == 1) {
                $cmsPage->is_active = $request->is_active;
                $msg = "Footer Activated Successfully!";
            } else {
                $cmsPage->is_active = $request->is_active;
                $msg = "Footer Deactivated Successfully!";
            }
            $cmsPage->save(); 
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }

    public function edit($id)
    {
        $model = FooterLink::findOrFail($id);
        $type = $model->type;   
        $type_id = $model->relation_data;
        $id = $model->id;
        $page_name = 'edit';
        return view('admin.footer.form', compact('model', 'id','page_name','type','type_id'));
    }

    public function update(Request $request,$id)
    {
            $model = FooterLink::findOrFail($id);
            $model->name = $request->name;
            $model->sort_order = $request->sort_order;
            $model->is_active = $request->is_active;
            $model->type = $request->type;
            $model->relation_data = implode(",",$request->dropdown);
            $model->save();
            
            // $data = FooterLink::updateOrCreate(array('type' =>$request->type));
            // if (!empty($data)) {
            //     $data->name = $request->name;
            //     $data->sort_order = $request->sort_order;
            //     $data->is_active = $request->is_active;
            //     $data->relation_data = implode(",",$request->dropdown);
            //     $data->save();
                $notification = array(
                    'message' => 'Footer updated successfully!',
                    'alert-type' => 'success'
                );
            return redirect(config('app.adminPrefix').'/footer/list')->with($notification);
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
        return Response::json(['data'=>$data,'selected'=>$ids]);
    }
}
