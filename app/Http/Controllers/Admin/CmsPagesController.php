<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use DataTables;
use App\Models\GlobalCurrency;
use App\Models\CmsPages;
use App\Models\CurrencyConversionRate;
use Carbon\Carbon;
use DB;

class CmsPagesController extends Controller
{
    public function index(Request $request)
    {
        $req = $request->all();
        if ($request->ajax()) {
            try {
                DB::statement(DB::raw('set @rownum=0'));
                $cmsPages = CmsPages::select('id','name','slug','content','is_active')->whereNull('deleted_at')
                    ->orderBy('updated_at', 'desc')->get();
                // dd($packages);
                return Datatables::of($cmsPages)->make(true);
            } catch (\Exception $e) {
                return view('errors.500');
            } 
        }
        $search = (isset($req['search']) ? $req['search'] : '');
        return view('admin.cms_pages.index',compact('search'));
    }

    public function create(Request $request)
    {
        $model = new CmsPages;
        $page_name = 'create';
        return view('admin.cms_pages.form', compact('page_name', 'model'));
    }

    public function store(Request $request)
    {
        try {
            $cmsPage = new CmsPages();
            $cmsPage->name = $request->name;
            $cmsPage->slug = $request->slug;
            $cmsPage->seo_title = $request->seo_title;
            $cmsPage->seo_description = $request->seo_description;
            $cmsPage->seo_meta_keyword = $request->seo_meta_keyword;
            $cmsPage->is_active = $request->is_active;
            $cmsPage->content = $request->content;
            $cmsPage->save();

            $notification = array(
                'message' => 'CMS Page added successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/cms-page/list')->with($notification);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect(config('app.adminPrefix').'/cms-page/list');
        }
    }

    public function delete(Request $request,$id)
    {
        $cmsPage = CmsPages::select('id')
            ->where('id', $id)
            ->first();

        if (!empty($cmsPage)) {
            $cmsPage->deleted_at = Carbon::now();
            $cmsPage->save();
            $result['status'] = 'true';
            $result['msg'] = "Cms Page Deleted Successfully!";
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
            $cmsPage = CmsPages::where('id', $request->cms_page_id)->first();
            if ($request->is_active == 1) {
                $cmsPage->is_active = $request->is_active;
                $msg = "Cms Page Activated Successfully!";
            } else {
                $cmsPage->is_active = $request->is_active;
                $msg = "Cms Page Deactivated Successfully!";
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
        $model = CmsPages::findOrFail($id);
        $page_name = 'edit';
        return view('admin.cms_pages.form', compact('model', 'page_name'));
    }

    public function update(Request $request,$id)
    {
        $cmsPage = CmsPages::findOrFail($id);
        if (!empty($cmsPage)) {
            $cmsPage->name = $request->name;
            $cmsPage->slug = $request->slug;
            $cmsPage->seo_title = $request->seo_title;
            $cmsPage->seo_meta_keyword = $request->seo_meta_keyword;
            $cmsPage->seo_description = $request->seo_description;
            $cmsPage->is_active = $request->is_active;
            $cmsPage->content = $request->content;
            $cmsPage->save();

            $notification = array(
                'message' => 'CMS Page updated successfully!',
                'alert-type' => 'success'
            );

            return redirect(config('app.adminPrefix').'/cms-page/list')->with($notification);
        }
    } 
} 
