<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProductCategories;
use App\Models\ProductImages;
use App\Models\Inquiries;
use App\Models\Products;
use App\Models\Professionals;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Html;

class InquiriesController extends Controller
{
    public function index()
    {
        $model = new Inquiries();
        $products = Products::pluck('title', 'id');
        // $professionals = DB::table('professionals')->get()->pluck('company_name', 'id');
        return view("admin.inquiries.index", ['model' => $model, 'products' => $products]);
    }

    public function professionalIndex()
    {
        $model = new Inquiries();
        $products = Professionals::pluck('company_name', 'user_id');
        // $professionals = DB::table('professionals')->get()->pluck('company_name', 'id');
        return view("admin.inquiries.professional_index", ['model' => $model, 'products' => $products]);
    }

    public function list(Request $request)
    {
        $req = $request->all();
        $fromDate = !empty($req['fromDate']) ? date('Y-m-d', strtotime($req['fromDate'])) : '';
        $toDate = !empty($req['toDate']) ? date('Y-m-d', strtotime($req['toDate'])) : '';
        // $userId = $req['userId'];
        $categoryId = $req['categoryId'];
        $type = isset($req['type'])?$req['type']:'product';
        $status = $req['status'];
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['inquiries.id', 'inquiries.first_name', 'inquiries.last_name', 'inquiries.email', 'inquiries.product_id', 'inquiries.message', 'inquiries.contact_time', 'inquiries.status', 'inquiries.created_at'];


        $total = Inquiries::selectRaw('count(*) as total')->where('type',$type);
        if (!empty($fromDate) && !empty($toDate)) {
            $total = $total->whereBetween(DB::raw("date_format(inquiries.created_at,'%Y-%m-%d')"), array($fromDate, $toDate));
        }
        // if (!empty($userId)) {
        //     $total = $total->where('inquiries.user_id', $userId);
        // }
        if (!empty($categoryId)) {
            //$total = $total->where('inquiries.category_id', $categoryId);
            $total = $total->where("inquiries.product_id",$categoryId);
        }
        $total = $total->first();
        $totalfiltered = $total->total;
        if ($type=='product') {
            $query = DB::table('inquiries')->select('inquiries.*','products.title')
                // ->selectRaw(DB::raw('inquiries.id,inquiries.title as productTitle,inquiries.price as productPrice,inquiries.category_id as productCategories,inquiries.status,inquiries.main_image as image,professionals.company_name as professionalName'))
                ->leftJoin('products', 'products.id', '=', 'inquiries.product_id')
                ->where('inquiries.type',$type)
                ->whereNull('inquiries.deleted_at');

        }else{
            $query = DB::table('inquiries')->select('inquiries.*','professionals.company_name as title')
                // ->selectRaw(DB::raw('inquiries.id,inquiries.title as productTitle,inquiries.price as productPrice,inquiries.category_id as productCategories,inquiries.status,inquiries.main_image as image,professionals.company_name as professionalName'))
                ->leftJoin('professionals', 'professionals.user_id', '=', 'inquiries.product_id')
                ->where('inquiries.type',$type)
                ->whereNull('inquiries.deleted_at');
        }
        if (!empty($fromDate) && !empty($toDate)) {
            $query = $query->whereBetween(DB::raw("date_format(inquiries.created_at,'%Y-%m-%d')"), array($fromDate, $toDate));
        }
        // if (!empty($userId)) {
        //     $query = $query->where('inquiries.user_id', $userId);
        // }
        if (!empty($categoryId)) {
            //$query = $query->where('inquiries.category_id', $categoryId);
            $query = $query->where("inquiries.product_id",$categoryId);
        }
        if (isset($status) && $status != '') {
            $query = $query->where("inquiries.status",$status);
        }

        $filteredq = DB::table('inquiries')->select('*')
            ->leftJoin('products', 'products.id', '=', 'inquiries.product_id')
            ->whereNull('inquiries.deleted_at');
        if (!empty($fromDate) && !empty($toDate)) {
            $filteredq = $filteredq->whereBetween(DB::raw("date_format(inquiries.created_at,'%Y-%m-%d')"), array($fromDate, $toDate));
        }
        // if (!empty($userId)) {
        //     $filteredq = $filteredq->where('inquiries.user_id', $userId);
        // }
        if (!empty($categoryId)) {
            //$filteredq = $filteredq->where('inquiries.category_id', $categoryId);
            $filteredq = $filteredq->where("inquiries.product_id",$categoryId);
        }
        if (!empty($status)) {
            //$filteredq = $filteredq->where('inquiries.category_id', $status);
            $filteredq = $filteredq->where("inquiries.status",$status);
        }

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('inquiries.first_name', 'like', '%' . $search . '%')
                    ->orWhere('inquiries.last_name', 'like', '%' . $search . '%')
                    ->orWhere('inquiries.email', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('inquiries.first_name', 'like', '%' . $search . '%')
                    ->orWhere('inquiries.last_name', 'like', '%' . $search . '%')
                    ->orWhere('inquiries.email', 'like', '%' . $search . '%');
            });
            $filteredq = $filteredq->selectRaw('count(*) as total')->first();
            $totalfiltered = $filteredq->total;
        }
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $data = [];
        foreach ($query as $key => $value) {
            $isActive = '';
            $action = '';
            $editUrl = route('editProduct', $value->id);
            // if ($value->status == 1) {
            //     // $activeClass = 'active ';
            //     $isActive .= '<button type="button" class="btn btn-sm btn-toggle btn-status active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            // } else {
            //     // $activeClass = ' ';
            //     $isActive .= '<button type="button" class="btn btn-sm btn-toggle btn-status toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            // }
            $isActive = ($value->status==1)?'Closed':(($value->status==2)?'In Progress':'Pending');
            // $isActive .= '<button type="button" class="btn btn-sm btn-toggle btn-status '.$activeClass.'toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" data-on="Yes1" data-off="No1"  aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';

            // $action .= '<a href="' . $editUrl . '"><i class="fa fa-edit" aria-hidden="true"></i></a> &nbsp; &nbsp;';
            // $action .= '<a class="products_delete text-danger" data-id="' . $value->id . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $status = '';
            if ($value->status == 1) {
                $status .= '<li class="nav-item"> <a class="nav-link active-inactive-link" data-id="' . $value->id . '" data-status="0" >Mark as Pending</a> </li>';
                $status .= '<li class="nav-item"> <a class="nav-link active-inactive-link" data-id="' . $value->id . '" data-status="2" >Mark as In Progress</a> </li>';
            }elseif ($value->status == 2) {
                $status .= '<li class="nav-item"> <a class="nav-link active-inactive-link" data-id="' . $value->id . '" data-status="0" >Mark as Pending</a> </li>';
                $status .= '<li class="nav-item"> <a class="nav-link active-inactive-link" data-id="' . $value->id . '" data-status="1" >Mark as Closed</a> </li>';
            }else{
                $status .= '<li class="nav-item"> <a class="nav-link active-inactive-link" data-id="' . $value->id . '" data-status="2" >Mark as In Progress</a> </li>';
                $status .= '<li class="nav-item"> <a class="nav-link active-inactive-link" data-id="' . $value->id . '" data-status="1" >Mark as Closed</a> </li>';
            }
            $action .= '<div class="d-inline-block dropdown">'
                                .'<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                                    .'<span class="btn-icon-wrapper pr-2 opacity-7">'
                                        .'<i class="fa fa-cog fa-w-20"></i>'
                                    .'</span>'
                                .'</button>'
                                .'<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                                    .'<ul class="nav flex-column">'
                                        .$status
                                        .'<li class="nav-item">'
                                            .'<a class="nav-link products_delete" data-id="' . $value->id . '">Delete</a>'
                                        .'</li>'
                                    .'</ul>'
                                .'</div>'
                            .'</div>';
            $data[] = [$value->id,$value->inq_number, $value->first_name, $value->last_name, $value->email, $value->title, $value->message, $value->contact_time, $isActive, $value->created_at, $action];
        }
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }

    public function activeInactive(Request $request)
    {
        try {
            $model = Inquiries::where('id', $request->id)->first();
            if ($request->status == 1) {
                $model->status = $request->status;
                // $msg = "Inquiry Activated Successfully!";
            } else {
                $model->status = $request->status;
                // $msg = "Inquiry Deactivated Successfully!";
            }
            $msg = "Inquiry status changed successfully!";
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
        $model = Inquiries::find($request->products_id);
        if (!empty($model)) {
            $model->delete();
            $result['status'] = 'true';
            $result['msg'] = "Inquiry Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
}
