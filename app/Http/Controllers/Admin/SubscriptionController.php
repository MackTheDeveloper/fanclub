<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SubscriptionExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\Fan;
use App\Models\FanPlaylist;
use App\Models\FanPlaylistSongs;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use DataTables;
use App\Models\GlobalCurrency;
use App\Models\CmsPages;
use App\Models\CurrencyConversionRate;
use Carbon\Carbon;
use DB;
use Excel;
use Response;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $req = $request->all();
        $subscriptions = SubscriptionPlan::getList();
        $search = (isset($req['search']) ? $req['search'] : '');
        return view("admin.subscription.index", compact('subscriptions', 'search'));
    }
    public function list(Request $request)
    {
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['users.firstname', 'subscriptions.email', '', '', '', 'subscriptions.status', 'subscriptions.payment_id', 'created_at'];

        $total = Subscription::selectRaw('count(*) as total')->whereNull('subscriptions.deleted_at')->first();
        $query = Subscription::whereNull('subscriptions.deleted_at')->select(
            'subscriptions.*',
            'users.firstname',
            'subscriptions.phone as phone',
            'subscription__plans.duration',
            'subscription__plans.price as amount',
            'subscriptions.status as status',
            'subscription__plans.subscription_name',
            'payment_id',
            'subscriptions.created_at'
        )
            ->leftJoin("subscription__plans", "subscriptions.subscription_plan", "subscription__plans.id")
            ->leftJoin("users", "subscriptions.customer_id", "users.id");
        $filteredq = Subscription::whereNull('subscriptions.deleted_at')->leftJoin("subscription__plans", "subscriptions.subscription_plan", "=", "subscription__plans.id")
            ->leftJoin("users", "subscriptions.customer_id", "=", "users.id");
        $totalfiltered = $total->total;
        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('users.firstname', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.email', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.created_at', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.payment_id', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('users.firstname', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.email', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.created_at', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.payment_id', 'like', '%' . $search . '%');
            });
        }
        if (isset($request->is_active)) {
            $filteredq = $filteredq->where('status', $request->is_active);
            $query = $query->where('status', $request->is_active);
        }
        if (isset($request->subscription) && $request->subscription != 'all') {
            $filteredq = $filteredq->where('subscription__plans.type', $request->subscription);
            $query = $query->where('subscription__plans.type', $request->subscription);
        }
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date($request->startDate);
            $endDate = date($request->endDate);
            $filteredq = $filteredq->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("DATE(subscriptions.created_at)"), array($startDate, $endDate));
            });
            $query = $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("DATE(subscriptions.created_at)"), array($startDate, $endDate));
            });
        }
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $filteredq = $filteredq->selectRaw('count(*) as total')->first();
        $totalfiltered = $filteredq->total;
        // $query = $query->offset($start)->limit($length)->get();
        // $firstname = Subscription::select('firstname')->leftJoin("users","subscriptions.customer_id","=","users.role_id")->first();
        $duration = Subscription::select('duration')->leftJoin("subscription__plans", "subscriptions.subscription_plan", "=", "subscription__plans.id")->first();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [$value->firstname, $value->email, $value->phone, $value->subscription_name, $value->amount, $value->status, $value->payment_id, getFormatedDate($value->created_at)];
        }
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }

    public function exportSubscription(Request $request)
    {
        // pre($request->all());
        try {
            return Excel::download(new SubscriptionExport(), 'fanclub Ltd._Subscription.xlsx');
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }
}
