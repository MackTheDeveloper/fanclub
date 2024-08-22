<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Professionals;
use App\Models\User;
use App\Models\Artist;
use App\Models\CmsPages;
use App\Models\Subscription;
use App\Models\Fan;
use App\Models\Forums;
use App\Models\Reviews;
use App\Models\Songs;
use App\Models\Transactions;
use DB;
use Carbon\Carbon;
use Response;

class DashboardController extends Controller
{
    /* ###########################################
    // Function: Dashboard
    // Description: Display analytical data for admin
    // Parameter: No Parameter
    // ReturnType: view
    */ ###########################################
    public function dashboard()
    {
        try {
            $newAccount = Admin::getNewAccountsDashboard();
            //$totalFan = Admin::getTotalFans();
            $totalFanToday = Admin::getTotalFansToday();
            //$totalArtist = Admin::getTotalArtist();
            $totalArtistToday = Admin::getTotalArtistToday();
            $totalArtistPending = Admin::getTotalArtistPending();
            $totalSubscriptionToday = Admin::getTotalSubscriptionToday();
            $totalSubscriptionSumToday = Admin::getTotalSubscriptionSumToday();


            $startDate = date('Y-m-d',strtotime("-1 days"));
            $endDate = date('Y-m-d',strtotime("-1 days"));
            $filterTotalFans = Admin::filterTotalFans($startDate, $endDate);
            $filterTotalArtist = Admin::filterTotalArtist($startDate, $endDate);
            $filterTotalSubscription = Admin::filterTotalSubscription($startDate, $endDate);
            $filterTotalSubscriptionSum = Admin::filterTotalSubscriptionSum($startDate, $endDate);

            // Top 5 Songs
            $topSongs = Admin::getTopSongs();

            // Top 5 Artist
            $topArtists = Admin::getTopArtists();



            // $lastFifteenDays = Admin::getLastFifteenDays();
            // $totalFrontendUsersInLastFifteenDays = Admin::getTotalFrontendUsersInLastFifteenDays($lastFifteenDays);
            // $totalProfessionalsInLastFifteenDays = Admin::getTotalProfessionalsInLastFifteenDays($lastFifteenDays);

            // return view('admin.dashboard', ['newAccount' => $newAccount, 'totalFrontendUsers' => $totalFrontendUsers, 'totalProfessionals' => $totalProfessionals, 'totalProducts' => $totalProducts, 'totalPositiveReview' => $totalPositiveReview, 'lastFifteenDays' => $lastFifteenDays, 'totalFrontendUsersInLastFifteenDays' => $totalFrontendUsersInLastFifteenDays, 'totalProfessionalsInLastFifteenDays' => $totalProfessionalsInLastFifteenDays]);
            return view('admin.dashboard', compact('totalFanToday', 'totalArtistToday', 'totalArtistPending', 'totalSubscriptionSumToday', 'totalSubscriptionToday', 'filterTotalFans', 'filterTotalArtist', 'filterTotalSubscription', 'filterTotalSubscriptionSum', 'topSongs', 'topArtists'));
        } catch (\Exception $e) {
            pre($e->getMessage());
        }
    }

    // Filter in dashboard
    public function dashboardFilter(Request $request)
    {
        $startDate = date($request->from_date);
        $endDate = date($request->to_date);

        $filterTotalFans = Admin::filterTotalFans($startDate, $endDate);
        $filterTotalArtist = Admin::filterTotalArtist($startDate, $endDate);
        $filterTotalSubscription = Admin::filterTotalSubscription($startDate, $endDate);
        $filterTotalSubscriptionSum = Admin::filterTotalSubscriptionSum($startDate, $endDate);

        $result['status'] = 'true';
        $result['filter_fans'] = $filterTotalFans;
        $result['filter_artist'] = $filterTotalArtist;
        $result['filter_subscription'] = $filterTotalSubscription;
        $result['filter_sales'] = $filterTotalSubscriptionSum;
        return $result;
    }

    public function listprofessionalrequest(Request $request)
    {
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['users.id', '', 'users.firstname', 'users.lastname', 'professionals.company_name', '', 'users.created_at'];


        $total = User::selectRaw('count(*) as total')
            ->join('professionals', 'professionals.user_id', '=', 'users.id')
            ->leftJoin('user_profile_photos', 'user_profile_photos.user_id', '=', 'users.id')
            ->where('users.is_deleted', 0)
            ->where('professionals.status', 0)
            ->first();

        $query = DB::table('users')
            ->selectRaw(DB::raw('users.id,users.firstname,users.lastname,professionals.company_name,users.created_at,user_profile_photos.image'))
            ->join('professionals', 'professionals.user_id', '=', 'users.id')
            ->leftJoin('user_profile_photos', 'user_profile_photos.user_id', '=', 'users.id')
            ->where('users.is_deleted', 0)
            ->where('professionals.status', 0);

        $filteredq = DB::table('users')
            ->join('professionals', 'professionals.user_id', '=', 'users.id')
            ->leftJoin('user_profile_photos', 'user_profile_photos.user_id', '=', 'users.id')
            ->where('users.is_deleted', 0)
            ->where('professionals.status', 0);

        $totalfiltered = $total->total;

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('users.firstname', 'like', '%' . $search . '%')
                    ->orWhere('users.lastname', 'like', '%' . $search . '%')
                    ->orWhere('professionals.company_name', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('users.firstname', 'like', '%' . $search . '%')
                    ->orWhere('users.lastname', 'like', '%' . $search . '%')
                    ->orWhere('professionals.company_name', 'like', '%' . $search . '%');
            });
            $filteredq = $filteredq->selectRaw('count(*) as total')->first();
            $totalfiltered = $filteredq->total;
        }
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $data = [];
        foreach ($query as $key => $value) {
            $action = '<div role="group" class="btn-group-sm btn-group">
        <button data-id=' . $value->id . ' class="fa fa-thumbs-up btn-shadow btn btn-primary"></button>
      </div>';
            $status = '<div class="badge badge-pill badge-danger">Pending</div>';
            $image = '<img class="rounded-circle" src="' . url('public/assets/images/user_profile/' . $value->image) . '" width="40" height="40" alt=""/>';
            $data[] = [$value->id, $image, $value->firstname, $value->lastname, $value->company_name, $status, $value->created_at, $action];
        }
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }

    /* public function monthlyGraph()
    {
        $totalArtist = $totalSale = $totalFans = $dates = [];
        $lastDate = date('t');

        for ($i = 1; $i <= $lastDate; $i++) {
            $dateI = date('Y-m') . '-' . (strlen($i) > 1 ? $i : '0' . $i);
            $dates[] = date('d', strtotime($dateI)) . date(' M Y');
            $totalArtist[] = Artist::getDaywiseCount($dateI);
            $totalFans[] = Fan::getDaywiseCount($dateI);
            $totalSale[] = Subscription::getDaywiseCount($dateI);
        }
        $json_data = [
            'dates_array' => $dates,
            'total_fans' => $totalFans,
            'total_artist' => $totalArtist,
            'total_sale' => $totalSale,
            'status' => true,
        ];

        return Response::json($json_data);
    } */

    public function monthlyGraph($duration = '')
    {
        $totalArtist = $totalSale = $totalFans = $dates = [];
        if ($duration == 'daily') {
            $lastFifteenDays = Admin::getLastFifteenDays();
            krsort($lastFifteenDays);
            foreach ($lastFifteenDays as $k => $v) {
                $dates[] = date('d M', strtotime($v));
                $totalArtist[]  = Artist::getDaywiseCount($v);
                $totalFans[] = Fan::getDaywiseCount($v);
                $totalSale[] = Transactions::getDaywiseCount($v);
            }
        } else if ($duration == 'monthly') {
            $lastTwelveMonths = Admin::getLastTwelveMonths();
            krsort($lastTwelveMonths);
            foreach ($lastTwelveMonths as $k => $v) {
                $month = date('m', strtotime($v));
                $year = date('Y', strtotime($v));
                $dates[] = date('M y', strtotime($v));
                $totalArtist[]  = Artist::getMonthwiseCount($month, $year);
                $totalFans[] = Fan::getMonthwiseCount($month, $year);
                $totalSale[] = Transactions::getMonthwiseCount($month, $year);
            }
        } else {
            $lastFiveYears = Admin::getLastFiveYears();
            krsort($lastFiveYears);
            foreach ($lastFiveYears as $k => $v) {
                $year = date('Y', strtotime($v));
                $dates[] = date('Y', strtotime($v));
                $totalArtist[]  = Artist::getYearwiseCount($year);
                $totalFans[] = Fan::getYearwiseCount($year);
                $totalSale[] = Transactions::getYearwiseCount($year);
            }
        }
        /* $totalArtist = $totalSale = $totalFans = $dates = [];
        $lastDate = date('t');

        for ($i = 1; $i <= $lastDate; $i++) {
            $dateI = date('Y-m') . '-' . (strlen($i) > 1 ? $i : '0' . $i);
            $dates[] = date('d', strtotime($dateI)) . date(' M Y');
            $totalArtist[] = Artist::getDaywiseCount($dateI);
            $totalFans[] = Fan::getDaywiseCount($dateI);
            $totalSale[] = Subscription::getDaywiseCount($dateI);
        } */
        $json_data = [
            'dates_array' => $dates,
            'total_fans' => $totalFans,
            'total_artist' => $totalArtist,
            'total_sale' => $totalSale,
            'status' => true,
        ];

        return Response::json($json_data);
    }

    public function reviewGraph()
    {
        return Reviews::getReviews();
    }

    public function serachDashboard(Request $request)
    {
        $selectedRadio = $request->selectedRadio;
        $search = $request->search;
        if ($selectedRadio == 'Artists') {
            $data = Artist::getSearchData($search, 25, '');
            $result['total'] = Artist::getSearchData($search, 0, 'getTotal');
            $result['data'] = $data;
            return $result;
        } else if ($selectedRadio == 'Fans') {
            $data = Fan::getSearchData($search, 25, '');
            $result['total'] = Fan::getSearchData($search, 0, 'getTotal');
            $result['data'] = $data;
            return $result;
        } else if ($selectedRadio == 'Songs') {
            $data = Songs::getSearchData($search, 25, '');
            $result['total'] = Songs::getSearchData($search, 0, 'getTotal');
            $result['data'] = $data;
            return $result;
        } else if ($selectedRadio == 'Subscriptions') {
            $data = Subscription::getSearchData($search, 25, '');
            $result['total'] = Subscription::getSearchData($search, 0, 'getTotal');
            $result['data'] = $data;
            return $result;
        } else if ($selectedRadio == 'Transactions') {
            $data = Transactions::getSearchData($search, 25, '');
            $result['total'] = Transactions::getSearchData($search, 0, 'getTotal');
            $result['data'] = $data;
            return $result;
        } else if ($selectedRadio == 'CMS') {
            $data = CmsPages::getSearchData($search, 25, '');
            $result['total'] = CmsPages::getSearchData($search, 0, 'getTotal');
            $result['data'] = $data;
            return $result;
        } else if ($selectedRadio == 'Forums') {
            $data = Forums::getSearchData($search, 25, '');
            $result['total'] = Forums::getSearchData($search, 0, 'getTotal');
            $result['data'] = $data;
            return $result;
        } else {
            $result['total'] = 0;
            $result['data'] = [];
            return $result;
        }
    }
    public function deleteArtist(Request $request)
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
}
