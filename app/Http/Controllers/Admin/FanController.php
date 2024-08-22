<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FanExport;
use App\Exports\SubscriptionExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Fan;
use App\Models\Country;
use App\Models\States;

use App\Models\FanPlaylist;
use App\Models\SubscriptionPlan;
use App\Models\FanPlaylistSongs;
use App\Models\UserProfilePhoto;
use App\Exports\FansExport;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Excel;
use PHPUnit\Framework\Constraint\Count;

class FanController extends Controller
{
    public function index(Request $request)
    {
        $req = $request->all();
        $subscriptions = SubscriptionPlan::getList();
        $country = Country::getListForDropdown();
        $search = (isset($req['search']) ? $req['search'] : '');
        return view("admin.fan.index", compact('subscriptions', 'country', 'search'));
    }

    public function list(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_fans_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_fans_delete');
        $isViewPlalist = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_fan_playlist_listing');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['', '', '', 'firstname', 'email', '', '', 'country', '', 'created_at'];


        $total = Fan::selectRaw('count(*) as total')->where('role_id', '3')->whereNull('deleted_at')->first();
        $query = Fan::with('subscriptionPlan')->whereNull('deleted_at')->where('role_id', '3');
        $filteredq = Fan::whereNull('deleted_at')->where('role_id', '3');
        $totalfiltered = $total->total;

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if (isset($request->is_active)) {
            $filteredq = $filteredq->where('is_active', $request->is_active);
            $query = $query->where('is_active', $request->is_active);
        }
        if (isset($request->subscription)) {
            $filteredq = $filteredq->where('current_subscription', $request->subscription);
            $query = $query->where('current_subscription', $request->subscription);
        }
        if (isset($request->country)) {
            $filteredq = $filteredq->where('country', $request->country);
            $query = $query->where('country', $request->country);
        }
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date($request->startDate);
            $endDate = date($request->endDate);
            $filteredq = $filteredq->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
            $query = $query->where(function ($q) use ($startDate, $endDate) {
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
            $editUrl = route('editFan', $value->id);
            $playList = route('FanPlaylist', $value->id);
            if ($value->is_active == 1) {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }

            $subaction = ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link" href="' . $editUrl . '" title="Edit">Edit</a>'
                . '</li>' : '';
            $subaction .= ($isDeletable) ? '<li class="nav-item">'
                . '<a class="nav-link fan_delete" data-id="' . $value->id . '" title="Delete">Delete</a>'
                . '</li>' : '';
            $subaction .= ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link" href="' . $playList . '" title="playlist">Playlist</a>'
                . '</li>' : '';
            $activeInactive = ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link active-inactive-link" title="Status" >Mark as ' . (($value->is_active == '1') ? 'Inactive' : 'Active') . '</a>'
                . '</li>' : '';
            if ($activeInactive) {
                $action .= '<div class="d-inline-block dropdown">'
                    . '<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                    . '<span class="btn-icon-wrapper pr-2 opacity-7">'
                    . '<i class="fa fa-cog fa-w-20"></i>'
                    . '</span>'
                    . '</button>'
                    . '<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                    . '<ul class="nav flex-column">'
                    . $subaction . $activeInactive
                    . '</ul>'
                    . '</div>'
                    . '</div>';
            }

            $profilePic = '<img width="50" height="50" src=' . UserProfilePhoto::getProfilePhoto($value->id) . '/>';
            $mySubscriptions = $value->subscriptionPlan ? $value->subscriptionPlan->subscription_name : '-';
            $classRow = $value->is_active ? "" : "row_inactive";
            $data[] = [$classRow, $action, $profilePic, $value->firstname . ' ' . $value->lastname, $value->email, $value->prefix, $value->phone, $value->country, $value->state, $value->gender, $mySubscriptions, $isActive, getFormatedDate($value->dob), getFormatedDate($value->created_at)];
        }
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }

    public function export()
    {
        try {
            return Excel::download(new FansExport, 'WishlistReport.xlsx');
        } catch (\Exception $ex) {
            dd($ex);
            return view('admin.errors.404');
        }
    }


    // public function add()
    // {
    //     $model = new Fan;
    //     $model->sort_order = Fan::getSortOrder();
    //     return view('admin.fan.form', compact('model'));
    // }

    // public function store(Request $request)
    // {
    //     try {
    //         $input = $request->all();
    //         $model = Fan::create($input);
    //         $notification = array(
    //             'message' => 'Fan added successfully!',
    //             'alert-type' => 'success'
    //         );
    //         return redirect(config('app.adminPrefix').'/fans/index')->with($notification);
    //     } catch (\Exception $e) {
    //         return redirect(config('app.adminPrefix').'/fans/index');
    //     }
    // }

    public function edit($id)
    {
        $model = Fan::findOrFail($id);
        $emojiwithcodes = Country::select('id', 'phonecode')->get();
        $countries = Country::getListForDropdown();
        $states = States::getListForDropdown();
        return view('admin.fan.form', compact('model', 'countries', 'states', 'emojiwithcodes'));
    }

    public function update(Request $request, $id)
    {
        try {
            $model = Fan::findOrFail($id);
            $input = $request->all();
            if ($request->hasFile('user_profile_photos')) {
                $fileObject = $request->file('user_profile_photos');
                UserProfilePhoto::uploadAndSaveProfilePhoto($fileObject, $id);
            }
            $model->update($input);
            $notification = array(
                'message' => 'Fan updated successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix') . '/fans/index')->with($notification);
        } catch (\Exception $e) {
            return redirect(config('app.adminPrefix') . '/fans/index');
        }
    }

    public function playlist($id)
    {
        $data = FanPlaylist::getList($id);
        $model = Fan::findOrFail($id);
        return view('admin.fan.playlist', compact('data', 'model'));
    }

    public function playlistSongs($id)
    {
        $data = FanPlaylistSongs::getList($id);
        $model = FanPlaylist::findOrFail($id);
        $model2 = Fan::findOrFail($model->user_id);
        return view('admin.fan.playlistsongs', compact('data', 'model', 'model2'));
    }

    public function playlistDelete($id)
    {
        $model = FanPlaylist::where('id', $id)->first();
        if (!empty($model)) {
            // $model->deleted_at = Carbon::now();
            $model->delete();
            $result['status'] = 'true';
            $result['msg'] = "Fan Playlist Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
        return redirect()->back()->with('message', $result['msg']);
    }

    public function playlistSongsDelete($id)
    {
        $model = FanPlaylistSongs::where('id', $id)->first();
        // $modelId = $model->playlist_id;
        if (!empty($model)) {
            // $model->deleted_at = Carbon::now();
            $model->delete();
            $result['status'] = 'true';
            $result['msg'] = "Playlist Song Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
        return redirect()->back()->with('message', $result['msg']);
    }

    public function activeInactive(Request $request)
    {
        try {
            $model = Fan::where('id', $request->fan_id)->first();
            if ($request->status == 1) {
                $model->is_active = $request->status;
                $msg = "Fan Activated Successfully!";
            } else {
                $model->is_active = $request->status;
                $msg = "Fan Deactivated Successfully!";
            }
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }

    public function activeInactivePlaylist(Request $request)
    {
        try {
            $model = FanPlaylist::where('id', $request->id)->first();
            if ($request->status == "1") {
                $model->status = $request->status;
                $msg = "Fan Playlist Activated Successfully!";
            } else {
                $model->status = $request->status;
                $msg = "Fan Playlist Deactivated Successfully!";
            }
            // pre($model)
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        } catch (\Exception $ex) {
            // print_r($ex->getMessage());
            return view('errors.500');
        }
    }

    public function delete(Request $request)
    {
        $model = Fan::where('id', $request->fan_id)->first();
        if (!empty($model)) {
            $model->email = $model->email . 'deleted' . now();
            $model->phone = $model->phone . 'deleted' . now();
            // $model->handle = $model->handle.'deleted'.now();
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Fan Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
    public function exportFan(Request $request)
    {
        try {
            return Excel::download(new FansExport(), 'fanclub Ltd._Fan.xlsx');
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }
    public function getStates(Request $request)
    {
        $country_id = Country::where('name', $request->country)->pluck('id')->first();
        $states = DB::table('states')->where('country_id', $country_id)->get();
        $data = array();
        $data['country'] = $request->country;
        $data['states'] = $states;
        return $data;
    }
}
