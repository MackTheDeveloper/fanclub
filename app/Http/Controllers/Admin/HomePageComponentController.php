<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePageComponent;
use App\Models\DynamicGroups;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use DataTables;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;
use Response;
use Html;
use Image;
use File;
use Session;


class HomePageComponentController extends Controller
{
	public function index()
	{
			return view("admin.homepage_component.index");
	}

	public function list(Request $request)
	{
			$isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_homepage_component_edit');
			$isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_homepage_component_delete');
			$req = $request->all();
			$start = $req['start'];
			$length = $req['length'];
			$search = $req['search']['value'];
			$order = $req['order'][0]['dir'];
			$column = $req['order'][0]['column'];
			$orderby = ['', 'name', 'type', 'sord_order','created_at'];


			$total = HomePageComponent::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
			$query = HomePageComponent::whereNull('deleted_at');
			$filteredq = HomePageComponent::whereNull('deleted_at');
			$totalfiltered = $total->total;

			if ($search != '') {
					$query->where(function ($query2) use ($search) {
							$query2->where('name', 'like', '%' . $search . '%')
									->orWhere('sort_order', 'like', '%' . $search . '%');
					});
					$filteredq->where(function ($query2) use ($search) {
							$query2->where('name', 'like', '%' . $search . '%')
									->orWhere('sort_order', 'like', '%' . $search . '%');
					});
			}
			if (isset($request->status)) {
					$filteredq = $filteredq->where('status', $request->status);
					$query = $query->where('status', $request->status);
			}
			if (isset($request->type)) {
					$type="".$request->type."";
					$filteredq = $filteredq->where('type', $type);
					$query = $query->where('type', $type);
			}
			if (!empty($request->startDate) && !empty($request->endDate)) {
					$startDate = date($request->startDate);
					$endDate = date($request->endDate);
					$filteredq = $filteredq->where(function($q) use ($startDate,$endDate){
							$q->whereBetween('created_at', [$startDate, $endDate]);
					});
					$query = $query->where(function($q) use ($startDate,$endDate){
							$q->whereBetween('created_at', [$startDate, $endDate]);
					});
			}
			$query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
			$filteredq = $filteredq->selectRaw('count(*) as total')->first();
			$totalfiltered = $filteredq->total;
			// pre($query);
			$data = [];
			foreach ($query as $key => $value) {
					$isActive = '';
					$action = '';
					$activeInactive = '';
					$editUrl = route('editHomePageComponent', $value->id);
					// if ($value->status == 1) {
					// 		$isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
					// } else {
			 	// 			$isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
					// }
					$activeInactive .= ($isEditable)?'<li class="nav-item">'
					.'<a class="nav-link" href="' . $editUrl . '" >Edit</a>'
					.'</li>':'';
					$activeInactive .= ($isEditable)?'<li class="nav-item">'
							.'<a class="nav-link active-inactive-link" data-id="'.$value->id.'" data-status="'.(($value->status=='1')?0:1).'" >Mark as '.(( $value->status == '1')?'Inactive':'Active').'</a>'
					.'</li>':'';

					$delete = ($isDeletable)?'<li class="nav-item">'
											.'<a class="nav-link delete" data-id="' . $value->id . '">Delete</a>'
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
													.$activeInactive.$delete
											.'</ul>'
									.'</div>'
							.'</div>';
					}
					if($value->type=='1')
					$type='Text';
					elseif($value->type=='2')
					$type='Banner';
					elseif($value->type=='3')
					$type='Dynamic Group';
					$classRow = $value->status?"":"row_inactive";
					$data[] = [$classRow,$action,$value->name, $type, $value->sort_order,getFormatedDate($value->created_at)];
			}
			$json_data = array(
					"draw" => intval($_REQUEST['draw']),
					"recordsTotal" => intval($total->total),
					"recordsFiltered" => intval($totalfiltered),
					"data" => $data,
			);
			return Response::json($json_data);
	}

	public function create(Request $request)
	{
			$model = new HomePageComponent();
			$baseUrl=$this->getBaseUrl();
			$va = HomePageComponent::getSortOrder();
	    	$model->sort_order = HomePageComponent::getSortOrder();
			$DynamicGroups = DynamicGroups::selectRaw('count(dynamic_group_items.id) as records,dynamic_groups.*')
														->leftJoin('dynamic_group_items','dynamic_group_items.group_id','=','dynamic_groups.id')
														->whereNull('dynamic_groups.deleted_at')
														->whereNull('dynamic_group_items.deleted_at')->groupBy('dynamic_groups.id')->get();
			$page_name = 'create';
			return view('admin.homepage_component.form', compact('page_name', 'model','baseUrl','DynamicGroups'));
	}

	public function store(Request $request)
	{
			try {
					$model = new HomePageComponent();

					$model->name = $request->name;
					$model->type = $request->type;
					$model->visibility = $request->visibility;
					if($request->type=='1')
					$model->text = $request->text;
					else if($request->type=='2'){
						if ($request->hasFile('banner_image')) {
                $photo = $request->file('banner_image');
                $ext = $photo->extension();
                $filename = rand().'_'.time().'.'.$ext;
                $filePath = public_path().'/assets/images/homepagecomponentbanner';
                $img = Image::make($photo->path());
                $width = config('app.homePageComponentImage.width');
                $height = config('app.homePageComponentImage.height');
                if($img->width() == $width && $img->height() == $height){
                    $photo->move($filePath.'/', $filename);
                }else{
										$photo->move($filePath.'/', $filename);
                }
                $model->banner_image = $filename;

            }
						$model->banner_url_type = $request->banner_url_type;
						$model->banner_url_type_id = $request->banner_url_type_id;
				  }
					else if($request->type=='3')
					$model->dynamic_group_id = $request->dynamic_group_id;
					$model->sort_order = $request->sort_order;
					$model->status = $request->status;
					$model->save();

					$notification = array(
							'message' => 'Home Page Component added successfully!',
							'alert-type' => 'success'
					);
					return redirect(config('app.adminPrefix').'/homepage-component/index')->with($notification);
			} catch (\Exception $e) {
					Session::flash('error', $e->getMessage());
					return redirect(config('app.adminPrefix').'/homepage-component/index');
			}
	}

	public function delete(Request $request,$id)
	{
		$home = HomePageComponent::select('id')
			->where('id', $id)
			->first();

		if (!empty($home)) {
			$home->deleted_at = date('Y-m-d H:i:s');
			$home->save();
			$result['status'] = 'true';
			$result['msg'] = "Home Page Component Deleted Successfully!";
			return $result;
		} else {
			$result['status'] = 'false';
			$result['msg'] = "Something went wrong!!";
			return $result;
		}
	}

	public function ActiveInactive(Request $request)
	{
		try {
			$home = HomePageComponent::where('id', $request->comp_id)->first();
			if ($request->status == 1) {
				$home->status = $request->status;
				$msg = "Home Page Component Activated Successfully!";
			} else {
				$home->status = $request->status;
				$msg = "Home Page Component Deactivated Successfully!";
			}
			$home->save();
			$result['status'] = 'true';
			$result['msg'] = $msg;
			return $result;
		} catch (\Exception $ex) {
			return view('errors.500');
		}
	}

	public function edit($id)
	{
		$model = HomePageComponent::findOrFail($id);
		$baseUrl=$this->getBaseUrl();
		//$va = HomePageComponent::getSortOrder();
		//$model->sort_order = HomePageComponent::getSortOrder();
		$DynamicGroups = DynamicGroups::selectRaw('count(dynamic_group_items.id) as records,dynamic_groups.*')
													->leftJoin('dynamic_group_items','dynamic_group_items.group_id','=','dynamic_groups.id')
													->whereNull('dynamic_groups.deleted_at')
													->whereNull('dynamic_group_items.deleted_at')->groupBy('dynamic_groups.id')->get();
		$page_name = 'edit';
		return view('admin.homepage_component.form', compact('model', 'page_name','baseUrl','DynamicGroups'));
	}
	public function update(Request $request, $id)
	{
		try {
				$model = HomePageComponent::findOrFail($id);
				if (!empty($model)) {
					$model->name = $request->name;
					$model->type = $request->type;
					$model->visibility = $request->visibility;
					if($request->type=='1')
					$model->text = $request->text;
					else if($request->type=='2'){
						if ($request->hasFile('banner_image')) {
                $photo = $request->file('banner_image');
                $ext = $photo->extension();
                $filename = rand().'_'.time().'.'.$ext;
                $filePath = public_path().'/assets/images/homepagecomponentbanner/';
                $img = Image::make($photo->path());
                $width = config('app.homePageComponentImage.width');
                $height = config('app.homePageComponentImage.height');
                if($img->width() == $width && $img->height() == $height){
                    $photo->move($filePath.'/', $filename);
                }else{
										$photo->move($filePath.'/', $filename);
                }
                $model->banner_image = $filename;
            }
						$model->banner_url_type = $request->banner_url_type;
						$model->banner_url_type_id = $request->banner_url_type_id;
				  }
					else if($request->type=='3')
					$model->dynamic_group_id = $request->dynamic_group_id;
					$model->sort_order = $request->sort_order;
					$model->status = $request->status;
					$model->save();

					$notification = array(
						'message' => 'Home Page Component updated successfully!',
						'alert-type' => 'success'
					);
					return redirect(config('app.adminPrefix').'/homepage-component/index')->with($notification);
				}
		} catch (\Exception $e) {
				Session::flash('error', $e->getMessage());
				return redirect(config('app.adminPrefix').'/homepage-component/index');
		}
	}

	public function uploadHomePageImage(Request $request)
	{
			$folder_name = 'ckeditor-home-page-image';
			uploadCKeditorImage($request, $folder_name);
	}
}
