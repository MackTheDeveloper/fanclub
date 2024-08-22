<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ComingInterest;
use App\Exports\LandingInterestExport;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Html;
use Excel;

class LandingInterestsController extends Controller
{
    public function index()
    {
        return view("admin.landing_interest.index");
    }

    public function list(Request $request)
    {
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_landing_interest_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['id', 'name','email', 'role','created_at'];


        $total = ComingInterest::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = ComingInterest::whereNull('deleted_at');
        $filteredq = ComingInterest::whereNull('deleted_at');
        $totalfiltered = $total->total;

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('role', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('role', 'like', '%' . $search . '%');
            });
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

        if (isset($request->type) && $request->type!='all') {
            $filteredq = $filteredq->where('role', $request->type);
            $query = $query->where('role', $request->type);
        }

        $filteredq = $filteredq->selectRaw('count(*) as total')->first();
        $totalfiltered = $filteredq->total;
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $data = [];
        foreach ($query as $key => $value) {
            $action = '';
            $editUrl = route('editHowItWorks', $value->id);
            $subaction = ($isDeletable)?'<li class="nav-item">'
            .'<a class="nav-link landing_interest_delete" data-id="' . $value->id . '">Delete</a>'
        .'</li>':'';
           
            if ($subaction) {
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
            $data[] = [$value->id, $action, $value->name, $value->email,ucfirst($value->role),getFormatedDate($value->created_at)];
        }
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }

    public function export(Request $request)
    {
        try{
            return Excel::download(new LandingInterestExport(), 'LandingpageInterested.xlsx');
        } catch(\Exception $ex) {
            dd($ex);
            return view('errors.500');
        }
    }

    public function delete(Request $request)
    {
        $model = ComingInterest::find($request->landing_interest_id);
        if (!empty($model)) {
            $model->delete();
            $result['status'] = 'true';
            $result['msg'] = "Interest Inquiry Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
}
