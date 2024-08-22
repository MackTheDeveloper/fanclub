<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProductCategories;
use App\Models\ProductImages;
use App\Models\Reviews;
use App\Models\Products;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Html;

class ReviewsController extends Controller
{
    public function index()
    {
        $model = new Reviews();
        $products = Products::whereNull('deleted_at')->get()->pluck('title', 'id');
        // $professionals = DB::table('professionals')->get()->pluck('company_name', 'id');
        return view("admin.reviews.index", ['model' => $model, 'products' => $products]);
    }

    public function list(Request $request)
    {
        $req = $request->all();
        $fromDate = !empty($req['fromDate']) ? date('Y-m-d', strtotime($req['fromDate'])) : '';
        $toDate = !empty($req['toDate']) ? date('Y-m-d', strtotime($req['toDate'])) : '';
        $product_id = $req['product_id'];
        $ratings = $req['ratings'];
        $status = $req['status'];
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['reviews.id', 'reviews.first_name', 'reviews.last_name', 'reviews.email', 'reviews.product_id', 'reviews.message', 'reviews.contact_time', 'reviews.status', 'reviews.created_at'];


        $total = Reviews::selectRaw('count(*) as total');
        if (!empty($fromDate) && !empty($toDate)) {
            $total = $total->whereBetween(DB::raw("date_format(reviews.created_at,'%Y-%m-%d')"), array($fromDate, $toDate));
        }
        if (!empty($product_id)) {
            $total = $total->where("reviews.product_id",$product_id);
        }

        if (!empty($ratings)) {
            $total = $total->where("reviews.reviews",$ratings);
        }
        $total = $total->first();
        $totalfiltered = $total->total;

        $query = Reviews::selectRaw('reviews.*,products.title,users.firstname,users.lastname,users.email')
            ->leftJoin('products', 'products.id', '=', 'reviews.product_id')
            ->leftJoin('users', 'reviews.user_id', 'users.id')
            ->whereNull('reviews.deleted_at');
        if (!empty($fromDate) && !empty($toDate)) {
            $query = $query->whereBetween(DB::raw("date_format(reviews.created_at,'%Y-%m-%d')"), array($fromDate, $toDate));
        }
        // if (!empty($userId)) {
        //     $query = $query->where('reviews.user_id', $userId);
        // }
        if (!empty($product_id)) {
            $query = $query->where("reviews.product_id",$product_id);
        }
        if (!empty($ratings)) {
            $query = $query->where("reviews.reviews",$ratings);
        }
        if (isset($status) && $status != '') {
            $query = $query->where("reviews.status",$status);
        }

        $filteredq = DB::table('reviews')->select('*')
            ->leftJoin('products', 'products.id', '=', 'reviews.product_id')
            ->leftJoin('users', 'reviews.user_id', 'users.id')
            ->whereNull('reviews.deleted_at');
        if (!empty($fromDate) && !empty($toDate)) {
            $filteredq = $filteredq->whereBetween(DB::raw("date_format(reviews.created_at,'%Y-%m-%d')"), array($fromDate, $toDate));
        }
        // if (!empty($userId)) {
        //     $filteredq = $filteredq->where('reviews.user_id', $userId);
        // }
        if (!empty($categoryId)) {
            //$filteredq = $filteredq->where('reviews.category_id', $categoryId);
            $filteredq = $filteredq->where("reviews.product_id",$categoryId);
        }
        if (!empty($status)) {
            //$filteredq = $filteredq->where('reviews.category_id', $status);
            $filteredq = $filteredq->where("reviews.status",$status);
        }

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                // $query2->where('reviews.first_name', 'like', '%' . $search . '%')
                //     ->orWhere('reviews.last_name', 'like', '%' . $search . '%')
                //     ->orWhere('reviews.email', 'like', '%' . $search . '%');
                $query2->where('users.firstname', 'like', '%' . $search . '%')
                    ->orWhere('users.lastname', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%')
                    ->orWhere('products.title', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                // $query2->where('reviews.first_name', 'like', '%' . $search . '%')
                //     ->orWhere('reviews.last_name', 'like', '%' . $search . '%')
                //     ->orWhere('reviews.email', 'like', '%' . $search . '%');
                $query2->where('users.firstname', 'like', '%' . $search . '%')
                    ->orWhere('users.lastname', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%')
                    ->orWhere('products.title', 'like', '%' . $search . '%');
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
            $status = ($value->status=='0')? 'Pending':(($value->status=='1')?'Accepted':'Rejected');
            $ratings = '<input value="'.$value->reviews.'" class="star-rating-show" style="width:120px;">';
            // $ratings = $value->reviews;
            // if ($value->status=='0') {
                // $action .= '<a class="review_action text-success mr-2" data-action="accept" data-id="' . $value->id . '"><i class="fa fa-check" aria-hidden="true"></i></a>';
                // $action .= '<a class="review_action text-danger  mr-2" data-action="reject" data-id="' . $value->id . '"><i class="fa fa-times" aria-hidden="true"></i></a>';
            // }
            // $action .= '<a class="review_delete text-danger" data-id="' . $value->id . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $action .= '<div class="d-inline-block dropdown">'
                                .'<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                                    .'<span class="btn-icon-wrapper pr-2 opacity-7">'
                                        .'<i class="fa fa-cog fa-w-20"></i>'
                                    .'</span>'
                                .'</button>'
                                .'<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                                    .'<ul class="nav flex-column">'
                                        .'<li class="nav-item">'
                                            .'<a class="nav-link active-inactive-link" data-id="' . $value->id . '" data-value="'.(( $value->status == 1)?0:1).'">Mark as '.(( $value->status == 1)?'Pending':'Accepted').'</a>'
                                        .'</li>'
                                        .'<li class="nav-item">'
                                            .'<a class="nav-link review_action" data-id="' . $value->id . '">Delete</a>'
                                        .'</li>'
                                    .'</ul>'
                                .'</div>'
                            .'</div>';
            // $data[] = ["id"=>$value->id, "firstname"=>$value->firstname, "lastname"=>$value->lastname, "email"=>$value->email, "title"=>$value->title, "ratings"=>$ratings, "message"=>$value->message, "status"=>$status, "created_at"=>$value->created_at->format('Y-m-d H:i:s'), "action"=>$action];
            $data[] = [$value->id, $value->firstname, $value->lastname, $value->email, $value->title, $ratings, $value->message, $status, $value->created_at->format('Y-m-d H:i:s'), $action];
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
         // try {
            // $model = Reviews::where('id', $request->product_id)->first();
            // $model = Reviews::find($request->products_id);
            $model = Reviews::find($request->id);
            // pre($model);
            if ($request->status == 1) {
                $model->status = $request->status;
                $msg = "Review Accepted Successfully!";
            } else {
                $model->status = $request->status;
                $msg = "Review Rejected Successfully!";
            }
            $model->save();
            Reviews::makeAvgProductReview($model->product_id);
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        // } catch (\Exception $ex) {
        //     return view('errors.500');
        // }
    }


    public function delete(Request $request)
    {
        $model = Reviews::find($request->products_id);
        if (!empty($model)) {
            $model->delete();
            $result['status'] = 'true';
            $result['msg'] = "Product Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
}
