<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Playlists;
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


class PlaylistController extends Controller
{
	public function index()
	{
			return view("admin.playlists.index");
	}

	public function list(Request $request)
	{
			$isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_playlist_edit');
			$isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_playlist_delete');
			$req = $request->all();
			$start = $req['start'];
			$length = $req['length'];
			$search = $req['search']['value'];
			$order = $req['order'][0]['dir'];
			$column = $req['order'][0]['column'];
			$orderby = ['', '', 'name','sort_order','status','created_at'];


			$total = Playlists::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
			$query = Playlists::whereNull('deleted_at');
			$filteredq = Playlists::whereNull('deleted_at');
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
					$editUrl = route('editPlaylist', $value->id);
					if ($value->status == 1) {
							$isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
					} else {
							$isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
					}

					$edit = ($isEditable)?'<li class="nav-item">'
					.'<a class="nav-link" href="' . $editUrl . '" >Edit</a>'
					.'</li>':'';
					$activeInactive = ($isEditable)?'<li class="nav-item">'
							.'<a class="nav-link active-inactive-link" >Mark as '.(( $value->status == '1')?'Inactive':'Active').'</a>'
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
											.$edit.$activeInactive.$delete
											.'</ul>'
									.'</div>'
							.'</div>';
					}
					$classRow = $value->status?"":"row_inactive";
					$data[] = [$classRow,$action,$value->name,$value->sort_order, $isActive,getFormatedDate($value->created_at)];
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
			$model = new Playlists();
			$baseUrl=$this->getBaseUrl();
			$va = Playlists::getSortOrder();
	    $model->sort_order = Playlists::getSortOrder();
			$DynamicGroups = DynamicGroups::selectRaw('count(dynamic_group_items.id) as records,dynamic_groups.*')
														->leftJoin('dynamic_group_items','dynamic_group_items.group_id','=','dynamic_groups.id')
														->whereNull('dynamic_groups.deleted_at')
														->whereNull('dynamic_group_items.deleted_at')->groupBy('dynamic_groups.id')->get();
			$page_name = 'create';
			return view('admin.playlists.form', compact('page_name', 'model','baseUrl','DynamicGroups'));
	}

	public function store(Request $request)
	{
			try {
					$model = new Playlists();
					$model->name = $request->name;
					$model->dynamic_group_id = $request->dynamic_group_id;
					$model->sort_order = $request->sort_order;
					$model->status = $request->status;
					$model->save();

					$notification = array(
							'message' => 'Playlist added successfully!',
							'alert-type' => 'success'
					);
					return redirect(config('app.adminPrefix').'/playlists/index')->with($notification);
			} catch (\Exception $e) {
					Session::flash('error', $e->getMessage());
					return redirect(config('app.adminPrefix').'/playlists/index');
			}
	}

	public function delete(Request $request,$id)
	{
		$playlist = Playlists::select('id')
			->where('id', $id)
			->first();

		if (!empty($playlist)) {
			$playlist->deleted_at = date('Y-m-d H:i:s');
			$playlist->save();
			$result['status'] = 'true';
			$result['msg'] = "Playlist Deleted Successfully!";
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
			$playlist = Playlists::where('id', $request->playlist_id)->first();
			if ($request->status == 1) {
				$playlist->status = $request->status;
				$msg = "Playlist Activated Successfully!";
			} else {
				$playlist->status = $request->status;
				$msg = "Playlist Deactivated Successfully!";
			}
			$playlist->save();
			$result['status'] = 'true';
			$result['msg'] = $msg;
			return $result;
		} catch (\Exception $ex) {
			return view('errors.500');
		}
	}

	public function edit($id)
	{
		$model = Playlists::findOrFail($id);
		$baseUrl=$this->getBaseUrl();
	//	$va = Playlists::getSortOrder();
		//$model->sort_order = Playlists::getSortOrder();
		$DynamicGroups = DynamicGroups::selectRaw('count(dynamic_group_items.id) as records,dynamic_groups.*')
													->leftJoin('dynamic_group_items','dynamic_group_items.group_id','=','dynamic_groups.id')
													->whereNull('dynamic_groups.deleted_at')
													->whereNull('dynamic_group_items.deleted_at')->groupBy('dynamic_groups.id')->get();
		$page_name = 'edit';
		return view('admin.playlists.form', compact('model', 'page_name','baseUrl','DynamicGroups'));
	}
	public function update(Request $request, $id)
	{
		try {
				$model = Playlists::findOrFail($id);
				if (!empty($model)) {
					$model->name = $request->name;
					$model->dynamic_group_id = $request->dynamic_group_id;
					$model->sort_order = $request->sort_order;
					$model->status = $request->status;
					$model->save();

					$notification = array(
						'message' => 'Playlist updated successfully!',
						'alert-type' => 'success'
					);
					return redirect(config('app.adminPrefix').'/playlists/index')->with($notification);
				}
		} catch (\Exception $e) {
				Session::flash('error', $e->getMessage());
				return redirect(config('app.adminPrefix').'/playlists/index');
		}
	}

}
