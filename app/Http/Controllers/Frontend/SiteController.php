<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Controllers\API\V1\AuthAPIController;
use App\Http\Controllers\API\V1\LocationAPIController;
use App\Http\Controllers\API\V1\SearchAPIController;
use App\Http\Controllers\API\V1\FaqAPIController;
use App\Http\Controllers\API\V1\DynamicGroupAPIController;
use Exception;
use Auth;
use Mail;
use Socialite;
use Response;
use Agent;
use App\Http\Controllers\API\V1\ArtistAPIController;
use App\Http\Controllers\API\V1\SongAPIController;
use Illuminate\Support\Facades\Session;
use App\Models\GlobalSettings;
use App\Models\CmsPages;
use App\Models\Country;
use App\Models\DynamicGroups;
use App\Models\HomePageBanner;
use App\Models\HomePageComponent;
use App\Models\Songs;
use App\Models\UserProfilePhoto;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    /* ###########################################
    // Function: home
    // Description: Display front end home page
    // Parameter: No Parameter
    // ReturnType: view
    */ ###########################################
    public function faq(Request $request)
    {
        try {
            $api = new FaqAPIController();
            $data = $api->index($request);
            $data = $data->getData();
            $content = $data->component;
            $content = componentWithNameObject($content);
            // pre($content);
            $seo_title = GlobalSettings::getSingleSettingVal('faq_seo_title');
            $seo_meta_keyword = GlobalSettings::getSingleSettingVal('faq_seo_meta_keyword');
            $seo_description = GlobalSettings::getSingleSettingVal('faq_seo_description');
            return view('frontend.site.faq', compact('content', 'seo_title', 'seo_meta_keyword', 'seo_description'));
        } catch (\Exception $e) {
            pre($e->getMessage());
        }
    }

    public function themeToggle(Request $request)
    {
        try {
            $api = new AuthAPIController();
            $data = $api->themeToggle($request);
            $data = $data->getData();
            return Response::json($data);
        } catch (\Exception $e) {
            pre($e->getMessage());
        }
    }

    /**
     * Allow fan to message artist or not
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function allowMessage(Request $request)
    {
        try {
            $api = new AuthAPIController();
            $data = $api->allowMessage($request);
            $data = $data->getData();
            return Response::json($data);
        } catch (\Exception $e) {
            pre($e->getMessage());
        }
    }

    public function dymanicGroupSlug($slug)
    {
        $dynamicGroup = DynamicGroups::where('slug', $slug)->first();
        if ($dynamicGroup->type == 1)
            return redirect()->route('showContactUs');
    }

    /**
     * Show the collectons of the artists and fans.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function collection(Request $request)
    {
        $currentPath = request()->path();
        $exploaded = explode('/', $currentPath);
        $dynamicGroup = DynamicGroups::where('slug', $exploaded[1])->first();
        if ($dynamicGroup) {
            $seo_title = $dynamicGroup->seo_title;
            $seo_meta_keyword = $dynamicGroup->seo_meta_keyword;
            $seo_description = $dynamicGroup->seo_description;
            if ($dynamicGroup->type == 1) // Artists
            {
                $api = new ArtistAPIController();
                $data = $api->getArtistsByDynamicGroup($dynamicGroup->id);
                $data = $data->getData();
                $content = $data->component;
                // pre($content);
                $title = $dynamicGroup->name;
                // $title = str_replace('-',' ',$exploaded[1]);
                return view('frontend.artist.artist-all', compact('content', 'title', 'seo_title', 'seo_meta_keyword', 'seo_description'));
            } else if ($dynamicGroup->type == 2) // Songs
            {
                $api = new SongAPIController();
                $data = $api->getSongsByDynamicGroup($dynamicGroup->id);
                $data = $data->getData();
                $content = $data->component;
                // pre($content->groupDetailData);
                if ($content->groupDetailData) {
                    $title = $dynamicGroup->name;
                    $slug = $dynamicGroup->slug;
                    return view('frontend.songs.song-all', compact('content', 'title', 'seo_title', 'seo_meta_keyword', 'seo_description', 'slug'));
                }else{
                    abort(404, 'Page not found');        
                }
            }
        } else {
            abort(404, 'Page not found');
        }
    }
    public function fanclubPlaylist($search = "")
    {
        //patch by nivedita for search page see all//
        try {
            $api = new DynamicGroupAPIController();
            $data = $api->index($search);
            $data = $data->getData();
            $content = $data->component;
            $content = componentWithNameObject($content);
            // pre($content);
            return view('frontend.auth.fanclub-playlist', compact('content', 'search'));
        } catch (\Exception $e) {
            pre($e->getMessage());
        }
    }

    public function imageResizeOnTheFly($name, $dimensional)
    {
        $name = base64_decode($name);
        $cache_image = \Image::cache(function ($image) use ($dimensional, $name) {
            $size    = $dimensional;
            $size   = explode('x', $size);
            //return $image->make(url('/photos/'.$name))->resize($size[0], $size[1]);
            // $name = "https://fanclub-media.s3.amazonaws.com/images/769160181_1638878213.png";
            return $image->make($name)->fit($size[0], $size[1]);
        }, 1440); // cache for 10 minutes

        return \Response::make($cache_image, 200, ['Content-Type' => 'image']);
        // try {

        //     $entry   = Image::where('name', '=', $name)->firstOrFail();
        //     $columnName = 'name';
        //     $fullUrl = Storage::disk('local')->get($entry->upload_path . $entry->{$columnName});
        //     $size    = $dimensional;

        //     if(!is_null($size) && !is_null($name)){
        //         $size = explode('x', $size);

        //         $cache_image = \Image::cache(function($image) use($size, $name,$fullUrl){

        //             //return $image->make(url('/photos/'.$name))->resize($size[0], $size[1]);

        //             return $image->make($fullUrl)->fit($size[0], $size[1]);
        //         }, 1440); // cache for 10 minutes

        //         return \Response::make($cache_image, 200, ['Content-Type' => 'image']);
        //     }
        // } catch (\Exception $e) {
        //     //echo $e->getMessage();die;
        //     $name = 'default_main_image.jpg';
        //     $fullUrl = Storage::disk('local')->get('default_main_image.jpg');
        //     $size    = $dimensional;
        //     if(!is_null($size) && !is_null($name)){
        //         $size = explode('x', $size);
        //         $cache_image = \Image::cache(function($image) use($size, $name,$fullUrl){
        //             //return $image->make(url('/photos/'.$name))->resize($size[0], $size[1]);
        //             return $image->make($fullUrl)->fit($size[0], $size[1]);
        //         }, 1440); // cache for 10 minutes

        //         return \Response::make($cache_image, 200, ['Content-Type' => 'image']);
        //     }
        // }
    }

    public function scriptForOptimizeImages()
    {
        /* $allSongs = Songs::selectRaw('icon')->get()->toArray();
        try {
            foreach ($allSongs as $key => $value) {
                if (strpos($value['icon'], 'square_192_192') == false) {
                $key = \Tinify\setKey("CMwdR8KhbhpMcKKKX3FStk6qZvMJd8M7");
                $filePath = $value['icon'];
                $source = \Tinify\fromFile($filePath);
                $source->store(array(
                    "service" => "s3",
                    "aws_access_key_id" => config('filesystems.disks.s3.key'),
                    "aws_secret_access_key" => config('filesystems.disks.s3.secret'),
                    "region" => config('filesystems.disks.s3.region'),
                    "headers" => array("Cache-Control" => "max-age=31536000, public"),
                    "path" => config('filesystems.disks.s3.bucket') . str_replace('https://fanclub-media-live.s3.amazonaws.com', '', $filePath)
                ));

                DB::table('tinify_image_count')
                    ->decrement('count', 1);
                }
            }
            echo "Success";
        } catch (\Tinify\Exception $e) {
            echo "Failed";
            pre($e);
        } */

        /* $allUserProfilePhotoes = UserProfilePhoto::selectRaw('image')->get()->toArray();
        $fileStoragePath = public_path() . '/assets/images/user_profile';
        try {
            foreach ($allUserProfilePhotoes as $key => $value) {
                $key = \Tinify\setKey("CMwdR8KhbhpMcKKKX3FStk6qZvMJd8M7");
                $filePath = $fileStoragePath . '/' .  $value['image'];
                $source = \Tinify\fromFile($filePath);
                $source->toFile($filePath);

                DB::table('tinify_image_count')
                    ->decrement('count', 1);
                
            }
            echo "Success";
        } catch (\Tinify\Exception $e) {
            echo "Failed";
            pre($e);
        } */

        /* $allHomePageBanner = HomePageBanner::selectRaw('image as imageNw')
        ->whereNull('deleted_at')
        ->get()->toArray();
        $fileStoragePath = public_path() . '/assets/images/HomePageBanner';
        try {
            foreach ($allHomePageBanner as $key => $value) {
                $key = \Tinify\setKey("CMwdR8KhbhpMcKKKX3FStk6qZvMJd8M7");
                $filePath = $fileStoragePath . '/' . $value['imageNw'];
                $source = \Tinify\fromFile($filePath);
                $source->toFile($filePath);

                DB::table('tinify_image_count')
                    ->decrement('count', 1);
                
            }
            echo "Success";
        } catch (\Tinify\Exception $e) {
            echo "Failed";
            pre($e);
        } */
    }
}
