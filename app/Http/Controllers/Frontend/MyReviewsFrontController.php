<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\API\V1\ArtistAPIController;
use App\Http\Controllers\API\V1\ReviewAPIController;
use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Reviews;
use App\Models\Songs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Controllers\API\V1\AuthAPIController;
use App\Http\Controllers\API\V1\LocationAPIController;
use App\Http\Controllers\API\V1\SearchAPIController;
use Exception;
use Auth;
use Mail;
use Socialite;
use Response;
use Agent;
use Illuminate\Support\Facades\Session;
use App\Traits\ReuseFunctionTrait;
use App\Http\Controllers\API\V1\BlogsAPIController;
use App\Http\Controllers\API\V1\PagesAPIController;
use App\Models\GlobalSettings;
use App\Models\CmsPages;
use App\Models\Country;

class MyReviewsFrontController extends Controller
{
    use ReuseFunctionTrait;

    public function index(Request $request)
    {
        $userId = User::getLoggedInId();
        $total = Reviews::where('customer_id', $userId)->count();
        $api = new ReviewAPIController();
        $data = $api->index($request);
        $data = $data->getData();
        $content = $data->component;
        $content = componentWithNameObject($content);
        return view('frontend.pages.my-reviews', compact('content', 'total'));
    }

    public function ajaxReviews(Request $request)
    {
        $userId = User::getLoggedInId();
        $total = Reviews::where('customer_id', $userId)->count();
        $api = new ReviewAPIController();
        $data = $api->index($request);
        $data = $data->getData();
        $content = $data->component;
        $content = componentWithNameObject($content);
        return view('frontend.pages.reviews-loadmore', compact('content', 'total'));
    }

    public function delete(Request $request, $id = "")
    {
        $api = new ReviewAPIController();
        $data = $api->deleteReview($request);
        $data = $data->getData();
        if ($data->statusCode == 200) {
            return Response::json($data);
        } else {
            if ($data->statusCode == 300) {
                abort(404, 'Page not found');
            }
        }
    }
    public function edit($id)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ReviewAPIController();
            $data = $api->edit($id);
            $data = $data->getData();
            $content = $data->component;
            return Response::json($data);
        } else {
            abort(404, 'Page not found');
        }
    }

    public function update(Request $request)
    {
        $api = new ReviewAPIController();
        $data = $api->editReview($request, $request->id);
        $data = $data->getData();
        if ($data->statusCode == 200) {
            // $notification = array(
            //     'message' => $data->message,
            //     'alert-type' => 'success'
            // );
            // return redirect()->back()->with($notification);
            return Response::json($data);
            // \Illuminate\Support\Facades\Session::flash('message', 'Review Updated Successfully!');
            // return redirect()->back();
        } else {
            if ($data->statusCode == 300) {
                return redirect('home')->withErrors($content)->withInput();
            }
        }
    }

    /**
     * Show add review popup.
     *
     * @param  int $songId
     * @param  int $artistID
     * @return \Illuminate\View\View
     */
    public function showAddReview($artistId, $songId)
    {
        return view('frontend.components.reviews.add.add-review-body', ['songId' => $songId, 'artistId' => $artistId]);
    }

    /**
     * Store review
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function AddReview(Request $request)
    {
        $api = new ReviewAPIController();
        $data = $api->create($request);
        $data = $data->getData();
        return Response::json($data);
    }
}
