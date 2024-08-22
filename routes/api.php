<?php

use App\Http\Controllers\API\V1\ArtistAPIController;
use App\Http\Controllers\API\V1\ForumAPIController;
use App\Http\Controllers\API\V1\MusicCategoryAPIController;
use App\Http\Controllers\API\V1\MusicGenreAPIController;
use App\Http\Controllers\API\V1\MusicLanguageAPIController;
use App\Http\Controllers\API\V1\SongAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\V1\AuthAPIController;
use App\Http\Controllers\API\V1\ReviewAPIController;
use App\Http\Controllers\API\V1\InquiriesAPIController;
use App\Http\Controllers\API\V1\BlogsAPIController;
use App\Http\Controllers\API\V1\PagesAPIController;
use App\Http\Controllers\API\V1\SecurityQuestionsAPIController;
use App\Http\Controllers\API\V1\SearchAPIController;
use App\Http\Controllers\API\V1\FanAPIController;
use App\Http\Controllers\API\V1\FanClubPlaylistController;
use App\Http\Controllers\API\V1\HomePageAPIController;

use App\Http\Controllers\API\V1\ContactUsAPIController;
use App\Http\Controllers\API\V1\FaqAPIController;
use App\Http\Controllers\API\V1\ArtistEventAPIController;
use App\Http\Controllers\API\V1\ArtistNewsAPIController;
use App\Http\Controllers\API\V1\CountryAPIController;
use App\Http\Controllers\API\V1\DynamicGroupAPIController;
use App\Http\Controllers\API\V1\ChatAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['prefix' => 'v1'], function () {
	Route::post('register', [AuthAPIController::class, 'register']);
    Route::post('login', [AuthAPIController::class,'login']);
    Route::post('verify-user', [AuthAPIController::class,'verifyUser']);
    Route::post('forgot-password', [AuthAPIController::class,'forgotPassword']);
    Route::post('delete-account', [AuthAPIController::class,'deleteAccount']);
    Route::post('verify-otp', [AuthAPIController::class,'verifyOTP']);
    Route::post('send-otp', [AuthAPIController::class,'resendOTP']);
    Route::post('reset-password', [AuthAPIController::class,'resetPassword']);
    Route::post('login-with-otp', [AuthAPIController::class,'LoginWithOTP']);

    // FAN STEPS
    Route::get('register/fan/{step}', [AuthAPIController::class, 'signupFan']);
    Route::get('register/artist', [AuthAPIController::class, 'signupArtist']);
    Route::post('register/fan/second', [AuthAPIController::class, 'secondStepFan']);
    Route::post('register/fan/third', [AuthAPIController::class, 'thirdStepFan']);

    // Blog API
    Route::get('country/list', [PagesAPIController::class,'countryList']);
    Route::get('state/list/{country?}', [PagesAPIController::class,'stateList']);

    // Blog API
    Route::get('blog/list/{limit?}/{blogCategory?}', [BlogsAPIController::class,'index']);
    Route::get('blog/recent', [BlogsAPIController::class,'recentBlogs']);
    Route::get('blog/detail/{id}', [BlogsAPIController::class,'blogDetail']);

    // homepage API
    Route::post('pages/homepage', [PagesAPIController::class,'homePageComponent']);

    // Blog Category API
    Route::get('blog-categories/list', [BlogsAPIController::class,'blogCategories']);

    Route::get('my-account/{type?}', [AuthAPIController::class,'getAccountLists']);

    // forum API
    Route::any('forums/list',[ForumAPIController::class,'index']);
    Route::any('forum-detail/{id}',[ForumAPIController::class,'commentIndex']);


    
    //Route::post('forum-create',[ForumAPIController::class,'createNewTopic']);
    //Route::any('forum-detail/{id?}',[ForumAPIController::class,'detailIndex']);

    // Artist API
    Route::get('artist-detail/{id}',[ArtistAPIController::class,'index']);
	Route::post('artist-likedislike',[ArtistAPIController::class,'ArtistsIncreaseLike']);
	Route::post('artist-reviews-list',[ArtistAPIController::class,'ArtistsReviewList']);
    Route::post('song-likedislike', [ArtistAPIController::class, 'SongsIncreaseLike']);
    Route::post('group-likedislike', [ArtistAPIController::class, 'GroupIncreaseLike']);

    // Search API
    Route::post('search',[SearchAPIController::class,'search']);

    // Setting Key and value
    Route::post('setting', [AuthAPIController::class,'getSetting']);

    
    // Music Management
    Route::get('music-category/list',[MusicCategoryAPIController::class,'index']);
    Route::get('music-genre/list',[MusicGenreAPIController::class,'index']);
    Route::get('music-language/list',[MusicLanguageAPIController::class,'index']);


    // Contact Us API
    Route::post('contact-us', [ContactUsAPIController::class,'create']);
    Route::get('contact-us', [ContactUsAPIController::class, 'index']);
    Route::get('faq', [FaqAPIController::class,'index']);

    Route::get('countries', [CountryAPIController::class,'index'])->name('getCountries');
    Route::get('home-page-components', [HomePageAPIController::class,'homePageData']);
    Route::get('variables', [PagesAPIController::class,'variableList'])->name('variableList');


    //Security questions
    Route::get('security-question-help', [SecurityQuestionsAPIController::class, 'getSecurityQuestionsList']);
    Route::post('security-question-help', [SecurityQuestionsAPIController::class, 'check']);

    // artist event List
    Route::any('artist-event-list/{id}', [ArtistEventAPIController::class, 'list']);
    // Route::any('artist-event-list/{id}', [ArtistEventAPIController::class, 'index']);
    Route::any('artist-news-list/{id}', [ArtistNewsAPIController::class, 'listing']);

    // Songs Detail
    Route::get('song/detail/{id}', [SongAPIController::class, 'SongsDetails']);

    Route::any('/all-artists/{search?}', [ArtistAPIController::class, 'allArtists']);
    Route::get('/all-songs/{search?}/{page?}', [SongAPIController::class, 'allSongs']);
    Route::get('fan-group/{search?}', [DynamicGroupAPIController::class, 'index']);

    Route::get('/artists/{id}', [ArtistAPIController::class, 'getArtistsByDynamicGroup']);
    Route::get('/songs/{id}', [SongAPIController::class, 'getSongsByDynamicGroup']);
    Route::get('fanclub-group/{id}', [SongAPIController::class, 'getSongsByDynamicGroup']);

    Route::get('genre/{id}/{page?}', [MusicGenreAPIController::class, 'getGenreById']);
    Route::get('category/{id}/{page?}', [MusicCategoryAPIController::class, 'getCategoryById']);
});

Route::group(['prefix' => 'v1' , 'middleware'=>'auth:api'], function () {
    // Auth API
    Route::post('theme-toggle', [AuthAPIController::class, 'themeToggle']);
    // Route::post('register-professional', [AuthAPIController::class,'RegisterAsProfessional']);
    Route::post('change-password', [AuthAPIController::class,'changePassword']);
    Route::get('fetch-profile', [AuthAPIController::class,'fetchProfile']);
    Route::get('check-subscription', [AuthAPIController::class, 'checkSubscription']);
    Route::post('update-profile', [AuthAPIController::class,'updateProfile']);
    Route::post('logout', [AuthAPIController::class,'logout']);
    Route::get('get-my-subscription', [FanAPIController::class, 'getMySubscriptonData']);
    Route::any('subscription-upgrade', [AuthAPIController::class, 'upgradeSubscription']);
    Route::any('subscription-cancel', [AuthAPIController::class, 'cancelSubscription']);


    // List all Account Menu


    // Songs Detail
    Route::post('song/create', [SongAPIController::class, 'SongCreate']);
    Route::post('song/create-test', [SongAPIController::class, 'SongCreateTest']);
    Route::get('song/edit/{id}', [SongAPIController::class, 'SongEdit']);
    Route::post('song/update', [SongAPIController::class, 'SongUpdate']);

    // Review API
    /* Route::get('reviews/list', [ReviewAPIController::class,'index']);
    Route::post('reviews/add', [ReviewAPIController::class,'create']); */
    Route::any('reviews/song/{id}', [ReviewAPIController::class,'indexReviewSongs']);
    Route::any('reviews/artist/{id}', [ReviewAPIController::class,'indexReviewArtists']);
    Route::post('reviews/add', [ReviewAPIController::class,'create']);
    Route::post('reviews/delete', [ReviewAPIController::class,'deleteReview']);
    Route::any('reviews/edit/{id}', [ReviewAPIController::class,'editReview']);
    Route::any('reviews/my-reviews', [ReviewAPIController::class,'index']);
    // Route::any('reviews/song-list', [ReviewAPIController::class,'getListOfAllReviews']);
    Route::post('reviews/reject-reviews', [ReviewAPIController::class,'rejectReview']);
    Route::any('reviews/song-list', [SongAPIController::class,'filteredReviewListArtist']);


	// Artist Events API
    Route::any('artist-event/list', [ArtistEventAPIController::class,'index']);
    Route::post('artist-event/add', [ArtistEventAPIController::class,'create']);
	Route::get('artist-event/edit/{id}', [ArtistEventAPIController::class, 'edit']);
	Route::post('artist-event/edit', [ArtistEventAPIController::class,'update']);
	Route::post('artist-event/delete', [ArtistEventAPIController::class,'delete']);

	// Artist News API
    Route::any('artist-news/list', [ArtistNewsAPIController::class,'list']);
    Route::post('artist-news/add', [ArtistNewsAPIController::class,'create']);
	Route::get('artist-news/edit/{id}', [ArtistNewsAPIController::class, 'edit']);
	Route::post('artist-news/edit', [ArtistNewsAPIController::class,'update']);
	Route::post('artist-news/delete', [ArtistNewsAPIController::class,'delete']);



    // Inquiry API
    Route::get('inquiries/list', [InquiriesAPIController::class,'index']);
    Route::post('inquiries/add', [InquiriesAPIController::class,'create']);

    // Blog Comment API
    Route::post('blog-comment/add', [BlogsAPIController::class,'addComments']);

    // User Posts API
    Route::post('user-posts/add', [UserPostsAPIController::class,'create']);
    Route::post('user-posts/toggle_like', [UserPostsAPIController::class,'addLikes']);
    Route::post('filtered-songs', [SongAPIController::class,'filteredList']);
    Route::post('artist-songs', [SongAPIController::class, 'artistSongs']);


    // Artist
    Route::get('artist-profile', [ArtistAPIController::class,'detail']);
    Route::get('artist-profile-detail', [ArtistAPIController::class,'artistProfile']);
    Route::post('artist-profile-detail', [ArtistAPIController::class,'updateDetails']);
    Route::post('artist-profile', [ArtistAPIController::class,'update']);
    Route::get('artist-dashboard', [ArtistAPIController::class,'dashboard']);

    // Fan & It's Playlist & It's favourite songs & It's favourite artist
    Route::get('fan-profile', [FanAPIController::class,'detail']);
    Route::post('fan-profile', [FanAPIController::class,'update']);
    Route::get('fan-playlist', [FanAPIController::class,'playlistindex']);
    Route::post('fan-playlist-with-song/create', [FanAPIController::class,'playlistCreateSongAdd']);
    Route::post('fan-playlist-songs/insert', [FanAPIController::class,'playlistSongAdd']);
    Route::get('fan-playlist-songs/remove/{id}', [FanAPIController::class,'playlistSongRemove']);
    Route::get('fan-playlist/remove/{id}', [FanAPIController::class,'playlistRemove']);
    Route::post('fan-playlist/update', [FanAPIController::class,'updateFanPlaylist']);
    Route::get('fan-playlist-songs/{id}', [FanAPIController::class,'playlistsongs']);
    Route::get('fan-favourite-songs/{search?}', [FanAPIController::class,'favouriteSongsNew']);
    Route::get('fan-recent-songs/{search?}', [FanAPIController::class, 'recentSongsNew']);
    Route::post('fan-favourite-song-action', [FanAPIController::class,'favouriteSongAction']);

    Route::get('fan-favourite-artists/{search?}', [ArtistAPIController::class,'myArtists']);
    Route::post('fan-favourite-artist-action', [FanAPIController::class,'favouriteArtistAction']);

    Route::any('fan-favourite-playlist/{search?}', [FanAPIController::class,'favouritePlaylist']);
    // Route::get('fan-favourite-playlist/{search?}', [FanAPIController::class,'favouritePlaylist']);
    Route::get('fan-playlist/{search?}', [FanAPIController::class,'myPlaylist']);
    
    
    Route::get('/fan-collection', [FanAPIController::class,'favouriteCollections']);
    
    // My Music
    Route::get('my-music', [SongAPIController::class,'myMusic']);
    

    // Songs List
    Route::post('filtered-songs', [SongAPIController::class,'filteredList']);

		// Artist List

    // Fan & It's Playlist & It's favourite songs & It's favourite artist
    Route::post('add-song-view', [SongAPIController::class,'SongsIncreaseView']);
    Route::post('add-song-stream', [SongAPIController::class, 'SongsIncreaseStream']);
    Route::post('add-song-recent', [SongAPIController::class, 'SongsAddToRecent']);
    Route::post('add-artist-view', [ArtistAPIController::class,'ArtistsIncreaseView']);

	// Fan club plalists
    Route::get('fanclub-playlists-details/{id}', [FanClubPlaylistController::class,'detail']);
	Route::get('fanclub-playlists', [FanClubPlaylistController::class,'playlistindex']);
    // Route::get('fanclub-group/{id}', [SongAPIController::class,'getSongsByDynamicGroup']);
    Route::get('fanclub-song-access/{id}/{resolution}', [SongAPIController::class,'getSongUrlById']);

	// Home Page Components
	Route::post('view-all', [HomePageAPIController::class,'getViewAll']);

    //Forum
    Route::post('forum-create',[ForumAPIController::class,'createTopic']);
    Route::post('forum-comment-create',[ForumAPIController::class,'createCommentMain']);
    Route::post('forum-likedislike',[ForumAPIController::class,'ForumIncreaseLike']);
    Route::post('forum-likedislike-comment',[ForumAPIController::class,'ForumCommentIncreaseLike']);


    //Security questions
    Route::get('get-security-question', [SecurityQuestionsAPIController::class,'getSecurityQuestions']);
    Route::post('set-security-question', [SecurityQuestionsAPIController::class,'setSecurityQuestions']);

    Route::post('search-tag-remove', [SearchAPIController::class,'searchTagRemove']);


    Route::post('/chat/initiate', [ChatAPIController::class, 'intiateChat']);
    Route::post('/chat/list-persons', [ChatAPIController::class, 'listPersons']);
    Route::post('/chat/get-person-chat', [ChatAPIController::class, 'getPersonChat']);
    Route::post('/chat/clear-chat', [ChatAPIController::class, 'clearChat']);
    Route::post('/chat/read-chat', [ChatAPIController::class, 'readChat']);
    Route::post('/chat/active-inactive', [AuthAPIController::class, 'allowMessage']);

    Route::post('/my-music-player', [FanAPIController::class, 'myMusicPlayerData']);
    Route::post('/get-data-for-my-music-player', [FanAPIController::class, 'getDataForMyMusicPlayer']);
});
