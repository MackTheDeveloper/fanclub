<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\HowItWorks;
use App\Models\GlobalLanguage;
use App\Models\SubscriptionPlan;

use Auth;
use Validator;
use File;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        return view("admin.subscription_plan.index");
    }

    public function list(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_subscription_plan_edit');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['','', '','subscription_name', '',''];


        $total = SubscriptionPlan::selectRaw('count(*) as total')->first();
        $query = SubscriptionPlan::whereNull('deleted_at');
        $filteredq = SubscriptionPlan::whereNull('deleted_at');
        $totalfiltered = $total->total;
        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('subscription_name', 'like', '%' . $search . '%');
            });

        }
        if (isset($request->type) && $request->type!='all') {
            $filteredq = $filteredq->where('type', $request->type);
            $query = $query->where('type', $request->type);
        }

        $filteredq = $filteredq->selectRaw('count(*) as total')->first();
        $totalfiltered = $filteredq->total;
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $data = [];
        foreach ($query as $key => $value) {
            $action = '';
            $editUrl = route('editSubscriptionPlan', $value->id);
            $subaction = ($isEditable)?'<li class="nav-item">'
                        .'<a class="nav-link" href="' . $editUrl . '">Edit</a>'
                    .'</li>':'';
            if ($subaction ){
                $action .= '<div class="d-inline-block dropdown">'
                    .'<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                        .'<span class="btn-icon-wrapper pr-2 opacity-7">'
                            .'<i class="fa fa-cog fa-w-20"></i>'
                        .'</span>'
                    .'</button>'
                    .'<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                        .'<ul class="nav flex-column">'
                        .$subaction
                        .'</ul>'
                    .'</div>'
                .'</div>';
            }
            $type =  $value->type == 1 ? "Monthly": "Yearly";
            $description = strlen($value->description) > 75 ? substr($value->description,0,75)."..." : $value->description;
            $classRow = "";
            $data[] = [$classRow,$action,$type,$value->subscription_name,$value->price,$description];
        }
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }

    public function edit($id)
    {
        $model = SubscriptionPlan::findOrFail($id);
        return view('admin.subscription_plan.form', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        try {
            $model = SubscriptionPlan::findOrFail($id);
            $model->update($input);
            $notification = array(
                'message' => 'Subscription Plan updated successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/subscription-plan/index')->with($notification);
        } catch (\Exception $e) {
            pre($e->getMessage());
            return redirect(config('app.adminPrefix').'/subscription-plan/index');
        }
    }
}
