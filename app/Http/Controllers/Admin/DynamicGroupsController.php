<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\DynamicGroups;
use App\Models\Songs;
use App\Models\Artist;
use App\Models\ArtistDetail;
use App\Models\UserProfilePhoto;
use App\Models\DynamicGroupItems;
use App\Models\MusicGenres;
use App\Models\MusicCategories;
use App\Models\MusicLanguages;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Session;

class DynamicGroupsController extends Controller
{
    public function index()
    {
        return view("admin.dynamic_groups.index");
    }

    public function list(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_dynamic_groups_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_dynamic_groups_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['', 'name', 'type', '', '', 'created_at'];


        $total = DynamicGroups::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = DynamicGroups::selectRaw('count(dynamic_group_items.id) as records,dynamic_groups.*')
            ->leftjoin("dynamic_group_items", function ($join) {
                $join->on("dynamic_group_items.group_id", "=", "dynamic_groups.id")
                    ->on("dynamic_group_items.type", "=", "dynamic_groups.type")
                    ->whereNull('dynamic_group_items.deleted_at');
            })
            ->whereNull('dynamic_groups.deleted_at');
        $filteredq = DynamicGroups::selectRaw('count(dynamic_group_items.id) as records,dynamic_groups.*')
            ->leftjoin("dynamic_group_items", function ($join) {
                $join->on("dynamic_group_items.group_id", "=", "dynamic_groups.id")
                    ->on("dynamic_group_items.type", "=", "dynamic_groups.type")
                    ->whereNull('dynamic_group_items.deleted_at');
            })
            ->whereNull('dynamic_groups.deleted_at');
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
        if (isset($request->status)) {
            $filteredq = $filteredq->where('status', $request->status);
            $query = $query->where('status', $request->status);
        }
        if (isset($request->type)) {
            $type = "" . $request->type . "";
            $filteredq = $filteredq->where('dynamic_groups.type', $type);
            $query = $query->where('dynamic_groups.type', $type);
        }
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date($request->startDate);
            $endDate = date($request->endDate);
            $filteredq = $filteredq->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(dynamic_groups.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
            $query = $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(dynamic_groups.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
        }
        $query = $query->groupBy('dynamic_groups.id')->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $data = [];
        foreach ($query as $key => $value) {
            $isActive = '';
            $action = '';
            $editUrl = route('editGroup', $value->id);
            if ($value->status == 1) {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }
            $edit = ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link" href="' . $editUrl . '" >Edit</a>'
                . '</li>' : '';
            $activeInactive = ($isEditable) ? '<li class="nav-item">'
                . '<a class="nav-link active-inactive-link" >Mark as ' . (($value->status == '1') ? 'Inactive' : 'Active') . '</a>'
                . '</li>' : '';

            $delete = ($isDeletable) ? '<li class="nav-item">'
                . '<a class="nav-link group_delete" data-id="' . $value->id . '">Delete</a>'
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
            $type = '';
            switch ($value->type) {
                case '1':
                    $type = 'Artists';
                    break;
                case '2':
                    $type = 'Songs';
                    break;
                case '3':
                    $type = 'Music Genres';
                    break;
                case '4':
                    $type = 'Music Categories';
                    break;
                case '5':
                    $type = 'Music Languages';
                    break;
            }
            $classRow = $value->status ? "" : "row_inactive";

            $data[] = [$classRow, $action, $value->name, $type, $value->records, $isActive, getFormatedDate($value->created_at)];
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
        $model = new DynamicGroups;
        $baseUrl = $this->getBaseUrl();
        $page_name = 'create';
        $model->allow_max = 30;
        return view('admin.dynamic_groups.form', compact('page_name', 'model', 'baseUrl'));
    }

    public function store(Request $request)
    {
        try {
            $model = new DynamicGroups();
            $model->name = $request->name;
            $string = $request->slug;
            $model->slug = getSlug($string, "", 'dynamic_groups', 'slug');
            $model->type = $request->type;
            $model->image_shape = $request->image_shape;
            $model->status = $request->status;
            $model->view_all = $request->view_all;
            $model->allow_max = !empty($request->allow_max) ? $request->allow_max : 30;
            $model->seo_title = $request->seo_title;
            $model->seo_meta_keyword = $request->seo_meta_keyword;
            $model->seo_description = $request->seo_description;
            $model->save();
            $notification = array(
                'message' => 'Dynamic group successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix') . '/dynamic-groups/edit/' . $model->id)->with($notification);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect(config('app.adminPrefix') . '/dynamic-groups/index');
        }
    }

    public function delete(Request $request, $id)
    {
        $model = DynamicGroups::select('id')
            ->where('id', $id)
            ->first();

        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "Group Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }

    public function edit($id)
    {
        $model = DynamicGroups::findOrFail($id);
        $baseUrl = $this->getBaseUrl();
        $searchCriteria = array("DC" => "Date Creation", "NL" => "Number of likes", "NV" => "Number of views");
        if ($model->type == '2'){
            $searchCriteria = array_merge($searchCriteria, array("ND" => "Number of downloads"));
        }
        return view('admin.dynamic_groups.form', compact('model', 'searchCriteria', 'baseUrl'));
    }

    public function update(Request $request, $id)
    {
        try {
            $model = DynamicGroups::findOrFail($id);
            $input = $request->all();
            $input['allow_max'] = !empty($request->allow_max) ? $request->allow_max : 30;
            if (!in_array($input['type'],['1','2'])) {
                $input['view_all'] = '0';
            }
            $model->update($input);
            $model->slug = $input['slug'];
            $model->image_shape = $input['image_shape'];
            $model->seo_title = $input['seo_title'];
            $model->seo_meta_keyword = $input['seo_meta_keyword'];
            $model->seo_description = $input['seo_description'];
            $model->update();
            $notification = array(
                'message' => 'Group updated successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix') . '/dynamic-groups/edit/' . $model->id)->with($notification);
        } catch (\Exception $e) {
            pre($e);
            return redirect(config('app.adminPrefix') . '/dynamic-groups/list');
        }
    }

    public function activeInactive(Request $request)
    {
        try {
            $model = DynamicGroups::where('id', $request->group_id)->first();
            if ($request->status == 1) {
                $model->status = $request->status;
                $msg = "Group Activated Successfully!";
            } else {
                $model->status = $request->status;
                $msg = "Group Deactivated Successfully!";
            }
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }
    public function getGroupDataList(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_artist_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_artist_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        if ($request->serachType == '1')
            $orderby = ['', '', 'firstname', 'created_at'];
        else
            $orderby = ['', '', 'name', 'created_at'];

        $GroupArtist = DynamicGroupItems::where('group_id', $request->groupId)->whereNull('deleted_at')->pluck('item_id', 'id')->toArray();
        //To get filtered data for artists
        if ($request->serachType == '1') {
            $total = ArtistDetail::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
            $query = ArtistDetail::join('users', 'users.id', '=', 'artist_detail.user_id')->whereNull('artist_detail.deleted_at')->whereNull('users.deleted_at');
            $filteredq = ArtistDetail::join('users', 'users.id', '=', 'artist_detail.user_id')->whereNull('artist_detail.deleted_at')->whereNull('users.deleted_at');
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
            if (!empty($request->criteria) && in_array("DC", $request->criteria) && !empty($request->startDate) && !empty($request->endDate)) {
                $startDate = date($request->startDate);
                $endDate = date($request->endDate);
                $filteredq = $filteredq->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween(DB::raw("date_format(artist_detail.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
                });
                $query = $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween(DB::raw("date_format(artist_detail.created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
                });
            }
            if (!empty($request->criteria) && in_array("NL", $request->criteria) && !empty($request->likeMin)) {
                $min = $request->likeMin;
                if (!empty($request->likeMax)) {
                    $max = $request->likeMax;
                    $filteredq = $filteredq->where(function ($q) use ($min, $max) {
                        $q->whereBetween('artist_detail.num_likes', [$min, $max]);
                    });
                    $query = $query->where(function ($q) use ($min, $max) {
                        $q->whereBetween('artist_detail.num_likes', [$min, $max]);
                    });
                } else {
                    $filteredq = $filteredq->where(function ($q) use ($min) {
                        $q->where('artist_detail.num_likes', '>=', $min);
                    });
                    $query = $query->where(function ($q) use ($min) {
                        $q->where('artist_detail.num_likes', '>=', $min);
                    });
                }
            }
            if (!empty($request->criteria) && in_array("NV", $request->criteria) && !empty($request->viewMin)) {
                $min = $request->viewMin;
                if (!empty($request->viewMax)) {
                    $max = $request->viewMax;
                    $filteredq = $filteredq->where(function ($q) use ($min, $max) {
                        $q->whereBetween('artist_detail.num_views', [$min, $max]);
                    });
                    $query = $query->where(function ($q) use ($min, $max) {
                        $q->whereBetween('artist_detail.num_views', [$min, $max]);
                    });
                } else {
                    $filteredq = $filteredq->where(function ($q) use ($min) {
                        $q->where('artist_detail.num_views', '>=', $min);
                    });
                    $query = $query->where(function ($q) use ($min) {
                        $q->where('artist_detail.num_views', '>=', $min);
                    });
                }
            }

            $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
            $data = [];
            foreach ($query as $key => $value) {
                $checked = '';
                $delete = '';
                $add = '';
                $key = '';
                $dataDelete = '';
                if (in_array($value->id, $GroupArtist)) {
                    $key = array_search($value->id, $GroupArtist);
                    $checked = 'checked';
                    $dataDelete = 'data-delete="' . $key . '"';

                    $actionbtn = '<a class="nav-link item_delete" data-id="' . $key . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                } else {
                    $actionbtn = '<a class="nav-link item_add" data-id="' . $value->id . '"><i class="fa fa-plus" aria-hidden="true"></i></a>';
                }
                $action = '';
                if ($actionbtn) {
                    $action .= $actionbtn;
                }
                $likes = 0;
                if (!empty($value->num_likes))
                    $likes = $value->num_likes;
                $views = 0;
                if (!empty($value->num_likes))
                    $views = $value->num_views;
                $profilePic = '<img width="50" height="50" src=' . UserProfilePhoto::getProfilePhoto($value->id) . '/>';
                $checkbox = '<input ' . $dataDelete . ' name="id[]" value="' . $value->id . '" id="id_' . $value->id . '" type="checkbox" ' . $checked . '/>';
                $data[] = [$checkbox, $profilePic, $value->firstname, getFormatedDate($value->created_at), $value->num_likes, $value->num_views, $action];
            }
        }
        //End To get foltered data for artists
        //To get foltered data for songs
        else if ($request->serachType == '2') {
            $total = Songs::has('activeArtist')->selectRaw('count(*) as total')->whereNull('deleted_at')->first();
            $query = Songs::has('activeArtist')->whereNull('deleted_at');
            $filteredq = Songs::has('activeArtist')->whereNull('deleted_at');
            $totalfiltered = $total->total;
            if ($search != '') {
                $query->where(function ($query2) use ($search) {
                    $query2->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('activeArtist', function ($qry) use ($search) {
                            $qry->where(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%');
                        });
                });
                $filteredq->where(function ($query2) use ($search) {
                    $query2->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('activeArtist', function ($qry) use ($search) {
                            $qry->where(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%');;
                        });
                });
                $filteredq = $filteredq->selectRaw('count(*) as total')->first();
                $totalfiltered = $filteredq->total;
            }
            if (!empty($request->criteria) && in_array("DC", $request->criteria) && !empty($request->startDate) && !empty($request->endDate)) {
                $startDate = date($request->startDate);
                $endDate = date($request->endDate);
                $filteredq = $filteredq->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween(DB::raw("date_format(created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
                });
                $query = $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween(DB::raw("date_format(created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
                });
            }
            if (!empty($request->criteria) && in_array("NL", $request->criteria) && !empty($request->likeMin)) {
                $min = $request->likeMin;
                if (!empty($request->likeMax)) {
                    $max = $request->likeMax;
                    $filteredq = $filteredq->where(function ($q) use ($min, $max) {
                        $q->whereBetween('num_likes', [$min, $max]);
                    });
                    $query = $query->where(function ($q) use ($min, $max) {
                        $q->whereBetween('num_likes', [$min, $max]);
                    });
                } else {
                    $filteredq = $filteredq->where(function ($q) use ($min) {
                        $q->where('num_likes', '>=', $min);
                    });
                    $query = $query->where(function ($q) use ($min) {
                        $q->where('num_likes', '>=', $min);
                    });
                }
            }
            if (!empty($request->criteria) && in_array("NV", $request->criteria) && !empty($request->viewMin)) {
                $min = $request->viewMin;
                if (!empty($request->viewMax)) {
                    $max = $request->viewMax;
                    $filteredq = $filteredq->where(function ($q) use ($min, $max) {
                        $q->whereBetween('num_views', [$min, $max]);
                    });
                    $query = $query->where(function ($q) use ($min, $max) {
                        $q->whereBetween('num_views', [$min, $max]);
                    });
                } else {
                    $filteredq = $filteredq->where(function ($q) use ($min) {
                        $q->where('num_views', '>=', $min);
                    });
                    $query = $query->where(function ($q) use ($min) {
                        $q->where('num_views', '>=', $min);
                    });
                }
            }
            if (!empty($request->criteria) && in_array("ND", $request->criteria) && !empty($request->downloadMin)) {
                $min = $request->downloadMin;
                if (!empty($request->downloadMax)) {
                    $max = $request->downloadMax;
                    $filteredq = $filteredq->where(function ($q) use ($min, $max) {
                        $q->whereBetween('num_downloads', [$min, $max]);
                    });
                    $query = $query->where(function ($q) use ($min, $max) {
                        $q->whereBetween('num_downloads', [$min, $max]);
                    });
                } else {
                    $filteredq = $filteredq->where(function ($q) use ($min) {
                        $q->where('num_downloads', '>=', $min);
                    });
                    $query = $query->where(function ($q) use ($min) {
                        $q->where('num_downloads', '>=', $min);
                    });
                }
            }
            $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
            $data = [];
            foreach ($query as $key => $value) {

                $checked = '';
                $delete = '';
                $add = '';
                $key = '';
                $dataDelete = '';
                if (in_array($value->id, $GroupArtist)) {
                    $key = array_search($value->id, $GroupArtist);
                    $checked = 'checked';
                    $dataDelete = 'data-delete="' . $key . '"';

                    $actionbtn = '<a class="nav-link item_delete" data-id="' . $key . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                } else {
                    $actionbtn = '<a class="nav-link item_add" data-id="' . $value->id . '"><i class="fa fa-plus" aria-hidden="true"></i></a>';
                }
                $action = '';
                if ($actionbtn) {
                    $action .= $actionbtn;
                }

                $icon = '<img width="50" height="50" src=' . $value->icon . '>';
                // $checkbox='<input '. $dataDelete.' name="id[]" value="'.$value->id.'" id="id_'.$value->id.'" type="checkbox" '.$checked.'/>';
                // $checkbox= '<div class="custom-checkbox custom-control custom-control-inline">
                //                 <input ' . $dataDelete . ' type="checkbox" value="' . $value->id . '" id="id_' . $value->id . '" name="id[]" class="custom-control-input" '. $checked.'>
                //                 <label class="custom-control-label" for="type2"></label>
                //             </div>';
                $checkbox = '<label class="ck only-ck">
                                <input ' . $dataDelete . ' type="checkbox" value="' . $value->id . '" id="id_' . $value->id . '" name="id[]" ' . $checked . '>
                                <span class="ck-mark"></span>
                            </label>';
                $data[] = [$checkbox, $icon, $value->name, getFormatedDate($value->created_at), $value->num_likes, $value->num_views, $value->num_downloads, $action];
            }
        }
        //End To get foltered data for songs
        //To get filtered data for Generes
        else if ($request->serachType == '3') {
            $total = MusicGenres::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
            $query = MusicGenres::whereNull('deleted_at');
            $filteredq = MusicGenres::whereNull('deleted_at');
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
                $checked = '';
                $delete = '';
                $add = '';
                $key = '';
                $dataDelete = '';
                if (in_array($value->id, $GroupArtist)) {
                    $key = array_search($value->id, $GroupArtist);
                    $checked = 'checked';
                    $dataDelete = 'data-delete="' . $key . '"';

                    $actionbtn = '<a class="nav-link item_delete" data-id="' . $key . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                } else {
                    $actionbtn = '<a class="nav-link item_add" data-id="' . $value->id . '"><i class="fa fa-plus" aria-hidden="true"></i></a>';
                }
                $action = '';
                if ($actionbtn) {
                    $action .= $actionbtn;
                }
                $image = '<img width="50" height="50" src=' . $value->image . '/>';
                $checkbox = '<input ' . $dataDelete . ' name="id[]" value="' . $value->id . '" id="id_' . $value->id . '" type="checkbox" ' . $checked . '/>';
                $data[] = [$checkbox, $image, $value->name, getFormatedDate($value->created_at), $action];
            }
        }
        //End To get foltered data for Generes
        //To get filtered data for Categories
        else if ($request->serachType == '4') {
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
                $checked = '';
                $delete = '';
                $add = '';
                $key = '';
                $dataDelete = '';
                if (in_array($value->id, $GroupArtist)) {
                    $key = array_search($value->id, $GroupArtist);
                    $checked = 'checked';
                    $dataDelete = 'data-delete="' . $key . '"';

                    $actionbtn = '<a class="nav-link item_delete" data-id="' . $key . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                } else {
                    $actionbtn = '<a class="nav-link item_add" data-id="' . $value->id . '"><i class="fa fa-plus" aria-hidden="true"></i></a>';
                }
                $action = '';
                if ($actionbtn) {
                    $action .= $actionbtn;
                }
                $image = '<img width="50" height="50" src=' . $value->image . '/>';
                $checkbox = '<input ' . $dataDelete . ' name="id[]" value="' . $value->id . '" id="id_' . $value->id . '" type="checkbox" ' . $checked . '/>';
                $data[] = [$checkbox, $image, $value->name, getFormatedDate($value->created_at), $action];
            }
        }
        //End To get filtered data for Categories
        //To get filtered data for Languages
        else if ($request->serachType == '5') {
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
                $checked = '';
                $delete = '';
                $add = '';
                $key = '';
                $dataDelete = '';
                if (in_array($value->id, $GroupArtist)) {
                    $key = array_search($value->id, $GroupArtist);
                    $checked = 'checked';
                    $dataDelete = 'data-delete="' . $key . '"';

                    $actionbtn = '<a class="nav-link item_delete" data-id="' . $key . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                } else {
                    $actionbtn = '<a class="nav-link item_add" data-id="' . $value->id . '"><i class="fa fa-plus" aria-hidden="true"></i></a>';
                }
                $action = '';
                if ($actionbtn) {
                    $action .= $actionbtn;
                }
                $image = '<img width="50" height="50" src=' . $value->image . '/>';
                $checkbox = '<input ' . $dataDelete . ' name="id[]" value="' . $value->id . '" id="id_' . $value->id . '" type="checkbox" ' . $checked . '/>';
                $data[] = [$checkbox, $image, $value->name, getFormatedDate($value->created_at), $action];
            }
        }
        //End To get filtered data for Languages
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }
    // To add single item to group
    public function addItems(Request $request)
    {
        try {
            if (!empty($request['itemID'])) {
                $groupItems = new DynamicGroupItems;
                $groupItems->group_id = $request['grpId'];
                $groupItems->item_id = $request['itemID'];
                $groupItems->type = $request['grpType'];
                if ($groupItems->save()) {
                    $result['status'] = 'true';
                    $result['msg'] = 'Item has been added.';
                    return $result;
                } else {
                    $result['status'] = 'false';
                    $result['msg'] = 'Your selected item is not added.';
                    return $result;
                }
            } else {
                $result['status'] = 'false';
                $result['msg'] = 'Please select at least one order.';
                return $result;
            }
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }
    // To add bulk item to group
    public function addBulkItems(Request $request)
    {
        try {
            if (!empty($request->checkedValues)) {
                $itemIds = [];
                $i = 0;
                foreach ($request->checkedValues as $itemId) {
                    if ($itemId != '')
                        $addItemsIds[$i++] = $itemId;
                }
                if (!empty($addItemsIds)) {
                    DynamicGroupItems::where('group_id', $request['grpId'])->update(['deleted_at' => date('Y-m-d H:i:s')]);
                    foreach ($addItemsIds as $addItemsId) {
                        $groupItems = new DynamicGroupItems;
                        $groupItems->group_id = $request['grpId'];
                        $groupItems->item_id = $addItemsId;
                        $groupItems->type = $request['grpType'];
                        $groupItems->save();
                    }
                    $result['status'] = 'true';
                    $result['msg'] = 'Item(s) has been added.';
                    return $result;
                } else {
                    $result['status'] = 'false';
                    $result['msg'] = 'Your selected items are not added.';
                    return $result;
                }
            } else {
                $result['status'] = 'false';
                $result['msg'] = 'Please select at least one order.';
                return $result;
            }
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }
    // To remove single item to group
    public function removeItems(Request $request)
    {
        try {
            if (!empty($request['itemID'])) {
                // if(DynamicGroupItems::where('group_id', $request['grpId'])->where('type',$request['grpId'])->where('item_id',$request['itemID'])->whereNull('deleted_at')->update(['deleted_at' => date('Y-m-d H:i:s')])){
                //     $result['status'] = 'true';
                //     $result['msg'] = 'Item has been removed.';
                //     return $result;
                // }

                if (DynamicGroupItems::where('id', $request['itemID'])->delete()) {
                    $result['status'] = 'true';
                    $result['msg'] = 'Item has been removed.';
                    return $result;
                } else {
                    $result['status'] = 'false';
                    $result['msg'] = 'Your selected item is not removed.';
                    return $result;
                }
            } else {
                $result['status'] = 'false';
                $result['msg'] = 'Please select at least one order.';
                return $result;
            }
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }

    // To remove bulk item to group
    public function removeBulkItems(Request $request)
    {
        try {
            if (!empty($request->checkedValues)) {
                // $itemIds = [];
                // $i = 0;
                // foreach($request->checkedValues as $itemId)
                // {
                //     if($itemId!='')
                //     $removeItemsIds[$i++] = $itemId;
                // }
                if (!empty($request->checkedValues)) {
                    // DynamicGroupItems::where('group_id', $request['grpId'])->where('type', $request['grpId'])->where('item_id', $removeItemsId)->whereNull('deleted_at')->update(['deleted_at' => date('Y-m-d H:i:s')]);
                    DynamicGroupItems::whereIn('id', $request->checkedValues)->delete();
                    $result['status'] = 'true';
                    $result['msg'] = 'Item(s) has been removed.';
                    return $result;
                } else {
                    $result['status'] = 'false';
                    $result['msg'] = 'Your selected items are not removed.';
                    return $result;
                }
            } else {
                $result['status'] = 'false';
                $result['msg'] = 'Please select at least one order.';
                return $result;
            }
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }

    public function getGroupDetails(Request $request)
    {
        try {
            if (!empty($request['grpId'])) {
                $baseUrl = $this->getBaseUrl();
                $DynamicGroups = DynamicGroups::selectRaw('count(dynamic_group_items.id) as records,dynamic_groups.*')
                    ->leftJoin('dynamic_group_items', 'dynamic_group_items.group_id', '=', 'dynamic_groups.id')
                    ->where('dynamic_groups.id', $request['grpId'])
                    ->whereNull('dynamic_group_items.deleted_at')->first();
                if ($DynamicGroups->type == '2')
                    $type = 'Songs';
                else if ($DynamicGroups->type == '1')
                    $type = 'Artists';
                else if ($DynamicGroups->type == '3')
                    $type = 'Genres';
                else if ($DynamicGroups->type == '4')
                    $type = 'Categories';
                else if ($DynamicGroups->type == '5')
                    $type = 'Languages';

                $dataUrl = '<a target="_blank" href="' . $baseUrl . config('app.adminPrefix') . '/dynamic-groups/edit/' . $DynamicGroups->id . '">' . $DynamicGroups->records . ' ' . $type . ' in this group</a>';
                return $dataUrl;
            }
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }
    public function getUtlTypeDetails(Request $request)
    {
        try {
            if (!empty($request['urlTypeId'])) {
                $urlTypeId = $request['urlTypeId'];
                switch ($urlTypeId) {
                    case '2':
                        $data = Artist::geArtistList();
                        break;
                    case '3':
                        $data = MusicGenres::getList();
                        break;
                    case '4':
                        $data = MusicCategories::getList();
                        break;
                    case '5':
                        $data = MusicLanguages::getList();
                        break;
                    case '6':
                        $data = Songs::getSongsList();
                        break;
                }

                //$dataUrl='<a target="_blank" href="'.$baseUrl.config('app.adminPrefix').'/dynamic-groups/edit/'.$DynamicGroups->id.'">'.$DynamicGroups->records.' '.$type.' in this group</a>';
                return json_encode($data);
            }
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }
}
