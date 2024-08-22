<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use DataTables;
use Carbon\Carbon;
use DB;
use Excel;
use Response;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $req = $request->all();
        $search = (isset($req['search']) ? $req['search'] : '');
        return view("admin.transaction.index",compact('search'));
    }
    public function list(Request $request)
    {
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['name', 'email', '', '','','status', 'payment_id','created_at'];

        $total = Transactions::selectRaw('count(*) as total')->whereNull('transactions.deleted_at')->first();
        $query = Transactions::whereNull('deleted_at');
        $filteredq = Transactions::whereNull('deleted_at');
        $totalfiltered = $total->total;
        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('plan', 'like', '%' . $search . '%')
                    ->orWhere('payment_id', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('plan', 'like', '%' . $search . '%')
                ->orWhere('payment_id', 'like', '%' . $search . '%')
                ->orWhere('amount', 'like', '%' . $search . '%');
            });
        }
        if (isset($request->is_active)) {
            $filteredq = $filteredq->where('status', $request->is_active);
            $query = $query->where('status', $request->is_active);
        }
        if (isset($request->plan) && $request->plan!='all') {
            $filteredq = $filteredq->where('plan', $request->plan);
            $query = $query->where('plan', $request->plan);
        }
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date($request->startDate);
            $endDate = date($request->endDate);
            $filteredq = $filteredq->where(function($q) use ($startDate,$endDate){
                $q->whereBetween(DB::raw("DATE(created_at)"), array($startDate, $endDate));
            });
            $query = $query->where(function($q) use ($startDate,$endDate){
                $q->whereBetween(DB::raw("DATE(created_at)"), array($startDate, $endDate));
            });
        }
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $filteredq = $filteredq->selectRaw('count(*) as total')->first();
        $totalfiltered = $filteredq->total;
        // $query = $query->offset($start)->limit($length)->get();
        // $firstname = Subscription::select('firstname')->leftJoin("users","subscriptions.customer_id","=","users.role_id")->first();
        //$duration = Subscription::select('duration')->leftJoin("subscription__plans", "subscriptions.subscription_plan","=","subscription__plans.id")->first();
        $data = [];
        foreach ($query as $key => $value)
        {
            $data[] = [$value->name, $value->email, $value->phone,$value->plan , $value->amount, $value->payment_id,$value->status ,getFormatedDate($value->created_at)];
        }
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }

     public function exportTransaction(Request $request)
    {
        // pre($request->all());
        try{
            return Excel::download(new TransactionExport(), 'fanclub Ltd._Transaction.xlsx');
        } catch(\Exception $ex) {
            return view('errors.500');
        }
    }

}
