<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ArtistExport;
use App\Exports\SubscriptionExport;
use App\Http\Controllers\Controller;
use App\Models\ArtistEvents;
use App\Models\ArtistNews;
use App\Models\Country;
use App\Models\States;
use App\Models\FanPlaylist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Artist;
use App\Models\ArtistDetail;
use App\Models\ArtistSocialMedia;
use App\Models\EmailTemplates;
use App\Models\UserProfilePhoto;
use App\Models\SocialMedia;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Excel;

class ArtistController extends Controller
{
    public function index(Request $request)
    {
        $req = $request->all();
        $country = Artist::getCountryList();
        $search = (isset($req['search']) ? $req['search'] : '');
        return view("admin.artist.index",compact('country','search'));
    }

    public function list(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_artist_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_artist_delete');
        $isSongList = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_songs_listing');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['', '', '', 'firstname', 'email', '', '', 'country', 'no_subscription', 'created_at'];


        $total = Artist::selectRaw('count(*) as total')->where('role_id', '2')->first();
        $query = Artist::selectRaw('users.*,artist_detail.no_subscription')->leftjoin('artist_detail','artist_detail.user_id','users.id')->where('role_id', '2');
        $filteredq = Artist::selectRaw('users.*,artist_detail.no_subscription')->leftjoin('artist_detail','artist_detail.user_id','users.id')->where('role_id', '2');
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
            $filteredq = $filteredq->selectRaw('count(*) as total')->first();
            $totalfiltered = $filteredq->total;
        }
        if (isset($request->is_active)) {
            $filteredq = $filteredq->where('is_active', $request->is_active);
            $query = $query->where('is_active', $request->is_active);
        }
        if (isset($request->country)) {
            $filteredq = $filteredq->where('country', $request->country);
            $query = $query->where('country', $request->country);
        }
        if (isset($request->approval)) {
            $filteredq = $filteredq->where('is_verify', $request->approval);
            $query = $query->where('is_verify', $request->approval);
        }
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date($request->startDate);
            $endDate = date($request->endDate);
            $filteredq = $filteredq->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(users.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
            $query = $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(users.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
        }
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->distinct()->get();
        $data = [];
        foreach ($query as $key => $value) {
            $action = '';
            $editUrl = route('editArtist', $value->id);
            $events = route('artistEvents', $value->id);
            $news = route('artistNews', $value->id);

            if ($value->view_map == 1) {
                $view_map_data = '<button type="button" class="btn btn-sm btn-toggle active toggle-is-view-map" data-id="' . $value->id . '" data-status="'.($value->view_map?0:1).'" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $view_map_data = '<button type="button" class="btn btn-sm btn-toggle toggle-is-view-map" data-id="'.$value->id.'" data-status="'.($value->view_map?0:1).'" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }

            $subaction = ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link" href="' . $editUrl . '" title="Edit">Edit</a>'
                . '</li>' : '';
            $subaction .= ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link" href="' . $events . '" title="Events">Events</a>'
                . '</li>' : '';
            $subaction .= ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link" href="' . $news . '" title="Events">News</a>'
                . '</li>' : '';

            $subaction .= ($isSongList) ? '<li class="nav-item">'
                . '<a class="nav-link showSongsList" data-id="'.$value->id.'" title="songs">List Songs</a>'
                . '</li>' : '';
            $activeInactive = ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link active-inactive-link" data-id="'.$value->id.'" data-status="'.($value->is_active?0:1).'">Mark as ' . (($value->is_active == '1') ? 'Inactive' : 'Active') . '</a>'
                . '</li>' : '';
            $activeInactive .= ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link approve-unaprove-link" data-id="'.$value->id.'" data-status="'.($value->is_verify?0:1).'">Mark as ' . (($value->is_verify == '1') ? 'Unapproved' : 'Approved') . '</a>'
                . '</li>' : '';
            $activeInactive .= ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link toggle-is-view-map" data-id="'.$value->id.'" data-status="'.($value->view_map?0:1).'">' . (($value->view_map == '1') ? 'Hide Map' : 'Show Map') . '</a>'
                . '</li>' : '';

            $subaction .= ($isDeletable) ? '<li class="nav-item">'
                . '<a class="nav-link artist_delete" data-id="' . $value->id . '" title="Delete" >Delete</a>'
                . '</li>' : '';
            if ($activeInactive || $subaction) {
                $action .= '<div class="d-inline-block dropdown">'
                    . '<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                    . '<span class="btn-icon-wrapper pr-2 opacity-7">'
                    . '<i class="fa fa-cog fa-w-20"></i>'
                    . '</span>'
                    . '</button>'
                    . '<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                    . '<ul class="nav flex-column">'
                    . $activeInactive . $subaction
                    . '</ul>'
                    . '</div>'
                     . '</div>';
            }

            $profile_type = "";
            if($value->gender == "other")
            {
                $profile_type = "Band";
            }
            else if($value->gender == "male")
            {
                $profile_type = "Solo Male";
            }
            else if($value->gender == "female")
            {
                $profile_type = "Solo Female";
            }
            $profilePic = '<img width="50" height="50" src=' . UserProfilePhoto::getProfilePhoto($value->id) . '/>';
            $classRow = $value->is_verify?($value->is_active?"":"row_inactive"):"row_unapproved";
            $data[] = [$classRow, $action, $profilePic, $value->firstname . ' ' . $value->lastname,$view_map_data, $value->email, $value->prefix,$value->phone,  getFormatedDate($value->dob),$value->country,$value->state,$profile_type,($value->no_subscription?:0),  getFormatedDate($value->created_at)];
        }
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }

    public function dashboardList(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_artists_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_artists_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['', 'firstname', 'email', '', '', 'country', 'created_at'];


        $total = Artist::selectRaw('count(*) as total')->where('role_id', '2')->where('is_verify', '0')->whereNull('deleted_at')->first();
        $query = Artist::whereNull('deleted_at')->where('role_id', '2')->where('is_verify', '0');
        $filteredq = Artist::whereNull('deleted_at')->where('role_id', '2')->where('is_verify', '0');
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
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date($request->startDate);
            $endDate = date($request->endDate);
            $filteredq = $filteredq->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
            $query = $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
        }

        $filteredq = $filteredq->selectRaw('count(*) as total')->first();
        $totalfiltered = $filteredq->total;
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $data = [];
        foreach ($query as $key => $value) {
            $action = '';
            $editUrl = route('editArtist', $value->id);

            $subaction = ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link" href="' . $editUrl . '" title="Edit">Edit</a>'
                . '</li>' : '';

            $activeInactive = ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link approve-unaprove-link" data-id="'.$value->id.'" data-status="'.($value->is_verify?0:1).'">Mark as ' . (($value->is_verify == '1') ? 'Unapproved' : 'Approved') . '</a>'
                . '</li>' : '';

            $subaction .= ($isDeletable) ? '<li class="nav-item">'
                . '<a class="nav-link artist_pending_delete" data-id="' . $value->id . '" title="Delete">Delete</a>'
                . '</li>' : '';
            if ($activeInactive || $subaction) {
                $action .= '<div class="d-inline-block dropdown">'
                    . '<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                    . '<span class="btn-icon-wrapper pr-2 opacity-7">'
                    . '<i class="fa fa-cog fa-w-20"></i>'
                    . '</span>'
                    . '</button>'
                    . '<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                    . '<ul class="nav flex-column">'
                    . $activeInactive . $subaction
                    . '</ul>'
                    . '</div>'
                     . '</div>';
            }

            $profilePic = '<img width="50" height="50" src=' . UserProfilePhoto::getProfilePhoto($value->id) . '/>';

            $data[] = [$action,$profilePic, $value->firstname . ' ' . $value->lastname, $value->email, $value->phone, $value->address, $value->country, getFormatedDate($value->created_at)];
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
        $model = new Artist;
        $modelArtistDetail = new ArtistDetail;
        $interests = DB::table('interest')->select(['id', 'topic'])->where('status', 1)->pluck('topic', 'id');
        $modelSocialMedia=SocialMedia::where('status',1)->whereNull('deleted_at')->get();
        $SocialMediaArr = [];
        if(!empty($modelSocialMedia)){
            foreach($modelSocialMedia as $key=>$val){
                $SocialMediaArr[$key]['id']=$val->id;
                $SocialMediaArr[$key]['name']=$val->name;
                $SocialMediaArr[$key]['url']='';
            }
        }
        return view('admin.artist.form', compact('model','modelArtistDetail','interests','SocialMediaArr'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $input['role_id'] = '2';
            $input['is_verify'] = 1;
            $model = Artist::create($input);
            $notification = array(
                'message' => 'Artist added successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/artists/index')->with($notification);
        } catch (\Exception $e) {
            return redirect(config('app.adminPrefix').'/artists/index');
        }
    }

    public function edit($id)
    {
        // pluck
        $phoneCodes = Country::pluck('phonecode','id');
        $countries = Country::getListForDropdown();
        $states = States::getListForDropdown();
        $modelArtistInterest = array();
        $model = Artist::findOrFail($id);
        $profile_type = $model->gender;
        $modelArtistDetail = DB::table('artist_detail')->select(DB::raw("artist_detail.bio,artist_detail.event,artist_detail.news_detail,artist_detail.interest"))
            ->join('users', 'users.id', '=', 'artist_detail.user_id')
            ->where('users.id', $id)
            ->first();
        if (empty($modelArtistDetail)) {
            $modelArtistDetail = new ArtistDetail();
        }

        $interests = DB::table('interest')->select(['id', 'topic'])->where('status', 1)->pluck('topic', 'id');

        $modelArtistSocialMedia = DB::table('artist_social_media')->select(DB::raw("artist_social_media.url,artist_social_media.social_id"))
            ->join('users', 'users.id', '=', 'artist_social_media.user_id')
            ->where('users.id', $id)
            ->whereNull('artist_social_media.deleted_at')
            ->get()
            ->toArray();
        if (empty($modelArtistSocialMedia))
            $modelArtistSocialMedia = new ArtistSocialMedia();
        // Developed by Nivedita 03-09-2021 for social media url//
        $modelSocialMedia=SocialMedia::where('status',1)->whereNull('deleted_at')->get();
        $SocialMediaArr=[];
        $i=0;
        if(!empty($modelSocialMedia)){
            foreach($modelSocialMedia as $val){
                $SocialMediaArr[$i]['id']=$val->id;
                $SocialMediaArr[$i]['name']=$val->name;
                if(!empty($modelArtistSocialMedia)){
                    foreach($modelArtistSocialMedia as $ArtistSocialMedia){
                        if(isset($ArtistSocialMedia->social_id) && $ArtistSocialMedia->social_id==$val->id){
                            $SocialMediaArr[$i]['url']=$ArtistSocialMedia->url;
                        }
                    }
                }
                else{
                    $SocialMediaArr[$i]['url']='';
                    $i++;
                }
            }
        }
        // End Developed by Nivedita 03-09-2021 for social media url//
        return view('admin.artist.form', compact('model', 'modelArtistDetail', 'modelArtistSocialMedia', 'modelArtistInterest', 'interests','SocialMediaArr','countries','states','profile_type','phoneCodes'));
    }

    public function update(Request $request, $id)
    {
        try {
            $model = Artist::findOrFail($id);
            $input = $request->all();
            $model->update($input);
            // Save artist details`
            /* ArtistDetail::where('user_id', $id)->delete();
            $inputArtistDetail['bio'] = $input['ArtistDetail']['bio'];
            $inputArtistDetail['event'] = $input['ArtistDetail']['event'];
            $inputArtistDetail['news_detail'] = $input['ArtistDetail']['news_detail'];
            $inputArtistDetail['interest'] = implode(',', array_values($input['ArtistDetail']['interest']));
            $inputArtistDetail['user_id'] = $id;
            $modelArtistDetail = ArtistDetail::create($inputArtistDetail); */


            $inputArtistDetail = ArtistDetail::firstOrNew(array('user_id' => $id));
            $inputArtistDetail->bio = $input['ArtistDetail']['bio'];
            $inputArtistDetail->event = isset($input['ArtistDetail']['event']) ? $input['ArtistDetail']['event'] : null;
            $inputArtistDetail->news_detail = isset($input['ArtistDetail']['news_detail']) ? $input['ArtistDetail']['news_detail'] : null;
            $inputArtistDetail->interest = isset($input['ArtistDetail']['interest']) ? implode(',', array_values($input['ArtistDetail']['interest'])) :null;
            $inputArtistDetail->user_id = $id;
            $inputArtistDetail->save();


            // Developed by Nivedita 03-09-2021 for social media url//
            ArtistSocialMedia::where('user_id',$id)->delete();
            foreach($input['url'] as $key=>$value){
                $modelArtistSocialMedia = new ArtistSocialMedia();
                $modelArtistSocialMedia->user_id=$id;
                $modelArtistSocialMedia->social_id=$key;
                $modelArtistSocialMedia->url=$value;
                $modelArtistSocialMedia->save();
            }
            // End Developed by Nivedita 03-09-2021 for social media url//
            if ($request->hasFile('user_profile_photos')) {
                $fileObject = $request->file('user_profile_photos');
                UserProfilePhoto::uploadAndSaveProfilePhoto($fileObject, $id);
            }

            // Toggling the view map of the artist
            !($request->has('view_map')) ? $model->view_map = "0" : $model->view_map = "1";
            $model->update();

            // notify the artist
            $notification = array(
                'message' => 'Artist updated successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/artists/index')->with($notification);
        } catch (\Exception $e) {
            return redirect(config('app.adminPrefix').'/artists/index');
        }
    }

    public function activeInactive(Request $request)
    {
        try {
            $model = Artist::where('id', $request->artist_id)->first();
            if ($request->status == 1) {
                $model->is_active = $request->status;
                $msg = "Artist Activated Successfully!";
            } else {
                $model->is_active = $request->status;
                $msg = "Artist Deactivated Successfully!";
            }
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }

    public function approve(Request $request)
    {
        try {
            $approved = 0;
            $model = Artist::where('id', $request->artist_id)->first();
            if ($request->approve == 1) {
                $model->is_verify = $request->approve;
                $msg = "Artist Approved Successfully!";

                $approved = 1;
            } else {
                $model->is_verify = $request->status;
                $msg = "Artist Disapproved Successfully!";
            }
            $model->save();

            if ($approved == 1) {
                $data = [
                    'NAME' => $model->firstname,
                    //'LAST_NAME' => $model->lastname,
                ];
                EmailTemplates::sendMail('artist-approval-email', $data, $model->email);
            }

            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        } catch (\Exception $ex) {
            pre($ex->getMessage());
            return view('errors.500');
        }
    }

    public function delete(Request $request)
    {
        $model = Artist::where('id', $request->artist_id)->first();
        if (!empty($model)) {
            $model->email = $model->email.'deleted'.now();
            $model->phone = $model->phone.'deleted'.now();
            // $model->handle = $model->handle.'deleted'.now();
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Artist Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
    public function exportArtist(Request $request)
    {
        try{
            return Excel::download(new ArtistExport(), 'Artist.xlsx');
        } catch(\Exception $ex) {
            dd($ex);
            return view('errors.500');
        }
    }
    public function events($id)
    {
        $data = ArtistEvents::getList($id);
        $model = Artist::findOrFail($id);
        return view('admin.artist.event', compact('data','model'));
    }
    public function eventsDelete($id)
    {
        $model = ArtistEvents::where('id', $id)->first();
        if (!empty($model)) {
            // $model->deleted_at = Carbon::now();
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Artist Event Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
    public function news($id)
    {
        $data = ArtistNews::getList($id);
        $model = Artist::findOrFail($id);
        return view('admin.artist.news', compact('data','model'));
    }
    public function newsDelete($id)
    {
        $model = ArtistNews::where('id', $id)->first();
        if (!empty($model)) {
            // $model->deleted_at = Carbon::now();
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Artist News Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
    public function viewMap(Request $request)
    {
        try {
            $model = Artist::where('id', $request->artist_id)->first();
            if ($request->status == 1) {
                $model->view_map = $request->status;
                $msg = "Artist Map View Enabled!";
            } else {
                $model->view_map = $request->status;
                $msg = "Artist Map View Disabled!";
            }
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }
}
