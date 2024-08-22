<?php

use Illuminate\Support\Facades\Route;

//Middleware
//
use App\Http\Controllers\Frontend\LandingController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\FanFrontController;
use App\Http\Controllers\Frontend\ForumFrontController;
use App\Http\Controllers\Frontend\MyReviewsFrontController;


use App\Http\Controllers\Frontend\ArtistFrontController;
use App\Http\Controllers\Frontend\SongsFrontController;
use App\Http\Controllers\Frontend\SiteController;
use App\Http\Controllers\API\V1\StateAPIController;
use App\Http\Controllers\Frontend\ArtistBannerFrontController;
use App\Http\Controllers\Frontend\ArtistEventFrontController;
use App\Http\Controllers\Frontend\ArtistNewsFrontController;
use App\Http\Controllers\Frontend\ChatFrontController;
use App\Http\Controllers\Frontend\UserSecurityQuestionController;
use App\Http\Controllers\Frontend\MusicGenresController;
use App\Http\Controllers\Frontend\CloudConvertController;
use App\Http\Controllers\Frontend\MusicCategoriesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Front without Logged In Routes
// Contact US
//Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/', [HomeController::class, 'home'])->name('home');

// Login
Route::get('/login', ['as' => 'login', HomeController::class, 'showLogin'])->name('login');
Route::post('/login', [HomeController::class, 'login']);

Route::post('/login-from-popup', [HomeController::class, 'loginFromPopup']);
Route::post('/login-using-otp-from-popup', [HomeController::class, 'loginUsingOtpFromPopup']);
Route::post('/otp-verification-from-popup', [HomeController::class, 'otpVerificationFromPopup']);
Route::post('/forgot-password-from-popup', [HomeController::class, 'forgotPasswordFromPopup']);

// Sign up
Route::get('/signup/fan', [HomeController::class, 'showSignupFan'])->name('showSignupFan');
Route::get('/signup/{introducer?}', [HomeController::class, 'showSignup'])->name('showSignup');
Route::post('/signup', [HomeController::class, 'signup']);
Route::post('/signup-subscription', [HomeController::class, 'secondSignup'])->name('secondSignup');
Route::post('/signup-payment', [HomeController::class, 'thirdSignup'])->name('thirdSignup');
Route::get('/artistsignup', [HomeController::class, 'showArtistSignup'])->name('showArtistSignup');
Route::post('/statelist', [StateAPIController::class, 'index'])->name('stateList');

Route::get('/login-using-otp', [HomeController::class, 'showLoginUsingOtp'])->name('showLoginUsingOtp');
Route::post('/login-using-otp', [HomeController::class, 'loginUsingOtp']);

Route::get('/otp-verification', [HomeController::class, 'showOtpVerification'])->name('showOtpVerification');
Route::post('/otp-verification', [HomeController::class, 'otpVerification']);

Route::get('/forgot-password', [HomeController::class, 'showForgotPassword'])->name('showForgotPassword');
Route::post('/forgot-password', [HomeController::class, 'forgotPassword'])->name('forgotPassword');

Route::get('/reset-password/{token}', [HomeController::class, 'showResetPassword'])->name('showResetPassword');
Route::post('/reset-password', [HomeController::class, 'resetPassword'])->name('resetPassword');
Route::get('/reset-password-success', [HomeController::class, 'resetPasswordSuccess'])->name('resetPasswordSuccess');

Route::get('/logout', [HomeController::class, 'logout'])->name('logout');



Route::post('/artist-fan-interest', [LandingController::class, 'submitForm'])->name('submitComingInterest');
// Route::get('/artist/{slug}', [ArtistFrontController::class, 'index'])->name('artistDetail');
// Route::get('/artist/{slug}/events', [ArtistEventFrontController::class, 'index'])->name('artistEventList');
// Route::get('/artist/{slug}/news', [ArtistNewsFrontController::class, 'index'])->name('artistNewsList');
Route::get('/search/{search?}', [HomeController::class, 'searchFront'])->name('searchFront');


Route::get('/about-us/{mobile?}/{darkmode?}', [HomeController::class, 'aboutUs'])->name('aboutUs');
Route::get('/terms-conditions/{mobile?}/{darkmode?}', [HomeController::class, 'termsConditions'])->name('termsConditions');
Route::get('/privacy-policy/{mobile?}/{darkmode?}', [HomeController::class, 'privacyPolicy'])->name('privacyPolicy');
Route::get('/cookie-policy/{mobile?}/{darkmode?}', [HomeController::class, 'cookiePolicy'])->name('cookiePolicy');

Route::get('/contact-us', [ContactUsController::class, 'showContactUs'])->name('showContactUs');
Route::post('/contact-us', [ContactUsController::class, 'store']);


Route::get('/forum-list', [ForumFrontController::class, 'index'])->name('forumsList');
Route::get('/forum-detail/{id}', [ForumFrontController::class, 'details'])->name('forumdetail');
Route::post('/forum-ajax-requests', [ForumFrontController::class, 'ajaxForum'])->name('forumAjaxReq');
Route::post('/forum-create', [ForumFrontController::class, 'newTopicCreate'])->name('forumCreate');
Route::post('/forum-comment-create', [ForumFrontController::class, 'newForumCommentCreate'])->name('forumCommentCreate');
Route::post('/forum-loadMore-comments', [ForumFrontController::class, 'loadmoreComments'])->name('forumLoadMoreComments');
Route::post('/forum-liked', [ForumFrontController::class, 'forumLikeDislike'])->name('forumLikeDislike');
Route::post('/forum-comment-liked', [ForumFrontController::class, 'forumCommentLikeDislike'])->name('forumCommentLikeDislike');
Route::post('/autocomplete/fetch', [ForumFrontController::class, 'autocompleteSearch'])->name('autocomplete.fetch');
Route::post('/sort-by-forum', [ForumFrontController::class, 'sortBy']);
//Route::get('/search', [ForumFrontController::class, 'search'])->name('searchReview');
//Route::post('/filter-review', [ForumFrontController::class, 'filterReview'])->name('filterReview');


Route::get('/faq', [SiteController::class, 'faq'])->name('faq');

Route::get('/resize-image/{image}/{size}', [SiteController::class, 'imageResizeOnTheFly'])->name('imageResizeOnTheFly');


// Cloud Convert webhook and api here
Route::get('/cloud-convert/index', [CloudConvertController::class, 'index']);
Route::post('/cloud-convert/webhook', [CloudConvertController::class, 'webhook']);

// optimize images
Route::get('/script-for-optimize-images', [SiteController::class, 'scriptForOptimizeImages'])->name('scriptForOptimizeImages');
Route::get('/cron-for-transaction-history', [HomeController::class, 'cronForTransactionHistory']);
Route::get('/cron-for-update-subscription-to-yearly', [HomeController::class, 'cronForUpdateSubscriptionToYearly']);
////CMS pages
//Route::get('/{slug}', [CmsController::class, 'index'])->name('cms');*/

// Guest Routes
Route::group(['middleware' => ['roleUser:guest']], function () {
    Route::get('/security-question-check', [UserSecurityQuestionController::class, 'showSecurityQuestionCheck'])->name('showSecurityQuestionCheck');
    Route::post('/security-question-check', [UserSecurityQuestionController::class, 'check']);
});
Route::get('/song-download-all', [SongsFrontController::class, 'getSongDownloadAll'])->name('getSongDownloadAll');

// artist detail page
Route::get('/artist/{slug}', [ArtistFrontController::class, 'index'])->name('artistDetail');
Route::post('/artist/filter-songs', [ArtistFrontController::class, 'filterSongs'])->name('filterSongs');

Route::get('artists/{dymanicGroupSlug}', [SiteController::class, 'collection'])->name('artistCollection');
Route::get('songs/{dymanicGroupSlug}', [SiteController::class, 'collection'])->name('songCollection');
Route::post('/download-all', [FanFrontController::class, 'downloadAll'])->name('downloadAll');
Route::post('/my-music-player', [FanFrontController::class, 'myMusicPlayer'])->name('myMusicPlayer');
Route::get('category/{slug}', [MusicCategoriesController::class, 'categoryDetails'])->name('categoryDetails');
Route::post('/category-loadMore', [MusicCategoriesController::class, 'loadmore'])->name('categoryLoadMore');
Route::get('/all-songs/{search?}', [SongsFrontController::class, 'allSongs'])->name('allSongs');
Route::post('/song-loadMore/{search?}', [SongsFrontController::class, 'loadmore'])->name('songLoadMore');
Route::get('genre/{slug}', [MusicGenresController::class, 'genreDetails'])->name('genreDetails');
Route::post('/genre-loadMore', [MusicGenresController::class, 'loadmore'])->name('genreLoadMore');
Route::get('fanclub-plyalist/{search?}', [SiteController::class, 'fanclubPlaylist'])->name('fanclubPlaylist');
Route::any('/all-artists/{search?}', [ArtistFrontController::class, 'allArtists'])->name('allArtists');
Route::get('/artist/{slug}/events', [ArtistEventFrontController::class, 'index'])->name('artistEventList');
Route::get('/artist/{slug}/news', [ArtistNewsFrontController::class, 'index'])->name('artistNewsList');
// For Both Artist, Fan
Route::group(['middleware' => ['auth', 'verifiedUser']], function () {

    // chat
    // Route::get('/chat', [ChatFrontController::class, 'index'])->name('chatModule');
    Route::get('/chat/{artist?}', [ChatFrontController::class, 'index'])->name('chatModule');
    Route::post('/chat/initiate', [ChatFrontController::class, 'intiateChat'])->name('intiateChat');
    Route::post('/chat/list-persons', [ChatFrontController::class, 'listPersons'])->name('listPersons');
    Route::post('/chat/get-person-chat', [ChatFrontController::class, 'getPersonChat'])->name('getPersonChat');
    Route::post('/chat/refresh-person-chat', [ChatFrontController::class, 'refreshPersonChat'])->name('refreshPersonChat');
    Route::post('/chat/clear-chat', [ChatFrontController::class, 'clearChat'])->name('clearChat');
    Route::post('/chat/read-chat', [ChatFrontController::class, 'readChat'])->name('readChat');

    Route::get('/security-question', [UserSecurityQuestionController::class, 'showSecurityQuestion'])->name('showSecurityQuestion');
    Route::post('/security-question', [UserSecurityQuestionController::class, 'store']);

    //Route::get('{dymanicGroupSlug}', [SiteController::class, 'dymanicGroupSlug'])->name('dymanicGroupSlug');
    Route::post('/theme-toggle', [SiteController::class, 'themeToggle'])->name('themeToggle');
    
    Route::get('/song-access/{slug}/{resolution?}', [SongsFrontController::class, 'getSongAccess'])->name('getSongAccess');
    Route::get('/song-download/{slug}/{resolution?}', [SongsFrontController::class, 'getSongDownload'])->name('getSongDownload');
    Route::get('/song-download-all', [SongsFrontController::class, 'getSongDownloadAll'])->name('getSongDownloadAll');
    

    Route::post('/get-music-player-data', [FanFrontController::class, 'getMusicPlayerData'])->name('getMusicPlayerData');
    Route::post('/get-music-player-song-data', [FanFrontController::class, 'getMusicPlayerSongData'])->name('getMusicPlayerSongData');
    Route::post('/get-music-player-review-data', [FanFrontController::class, 'getMusicPlayerReviewData'])->name('getMusicPlayerReviewData');

    Route::get('/change-password', [HomeController::class, 'showChangePassword'])->name('showChangePassword');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('changePassword');
});

// For Artist
Route::group(['middleware' => ['auth', 'verifiedUser', 'roleUser:2']], function () {
    // Artist Profile
    Route::get('/artist-profile', [ArtistFrontController::class, 'artistProfile'])->name('ArtistProfile');
    Route::get('/edit-artist-profile', [ArtistFrontController::class, 'editProfile'])->name('editProfileArtist');


    Route::get('/song-list-review', [ArtistFrontController::class, 'artistSongListForReview'])->name('artistSongListForReview');
    Route::post('/artist/filter-songs-review', [ArtistFrontController::class, 'artistReviewSongFilter'])->name('filterArtistSongsReview');
    Route::get('/my-song', [ArtistFrontController::class, 'songList'])->name('songList');
    Route::get('/song/upload', [SongsFrontController::class, 'add'])->name('SongUploadView');
    Route::post('/song/upload', [SongsFrontController::class, 'upload']);
    Route::get('/song/edit/{id}', [SongsFrontController::class, 'edit'])->name('SongEditView');
    Route::post('/song/update', [SongsFrontController::class, 'update'])->name('SongUpdate');

    Route::post('/artist/banner', [ArtistBannerFrontController::class, 'create'])->name('artistBannerCreate');
    Route::post('/artist/banner/delete', [ArtistBannerFrontController::class, 'delete'])->name('artistBannerDelete');

    Route::post('/artist/news', [ArtistNewsFrontController::class, 'create'])->name('artistNewsCreate');
    Route::get('/artist/news/edit/{id}', [ArtistNewsFrontController::class, 'edit'])->name('artistNewsEdit');
    Route::post('/artist/news/edit/{id}', [ArtistNewsFrontController::class, 'update'])->name('artistNewsUpdate');
    Route::post('/artist/news/delete', [ArtistNewsFrontController::class, 'delete'])->name('artistNewsDelete');

    Route::get('/artist/event/create', [ArtistEventFrontController::class, 'create'])->name('artistEventCreate');
    Route::post('/artist/event/create', [ArtistEventFrontController::class, 'store'])->name('artistEventStore');
    Route::get('/artist/event/edit/{id}', [ArtistEventFrontController::class, 'edit'])->name('artistEventEdit');
    Route::post('/artist/event/edit/{id}', [ArtistEventFrontController::class, 'update'])->name('artistEventUpdate');
    Route::post('/artist/event/delete', [ArtistEventFrontController::class, 'delete'])->name('artistEventDelete');

    Route::post('/updateArtistProfile', [ArtistFrontController::class, 'updateProfile']);
    Route::get('/artist-dashboard', [ArtistFrontController::class, 'dashboard'])->name('ArtistDashboard');
    Route::post('/artist-detail-update', [ArtistFrontController::class, 'artistDetailUpdate'])->name('ArtistDetailUpdate');
    Route::post('/artist/filter-artist-songs', [ArtistFrontController::class, 'artistSongFilter'])->name('filterArtistSongs');
    Route::post('/artist/reject-review', [ArtistFrontController::class, 'rejectReview'])->name('rejectReview');

    Route::get('/song-review/{id}', [ArtistFrontController::class, 'artistSongReview'])->name('artistSongReview');
    Route::post('/song-reviews-loadMore', [ArtistFrontController::class, 'loadmore'])->name('myReviewLoadMore');
    Route::post('/allow-message', [SiteController::class, 'allowMessage'])->name('allowMessage');
    Route::get('/switch-artist-fan', [HomeController::class, 'switchArtistFan'])->name('switchArtistFan');
});

// For Fan
Route::group(['middleware' => ['auth', 'verifiedUser', 'roleUser:3']], function () {
    // Artist Add Remove Like
    Route::post('/artist-like-dislike', [ArtistFrontController::class, 'artistLikeDislike'])->name('artistLikeDislike');
    Route::post('/song-like-dislike', [ArtistFrontController::class, 'songLikeDislike'])->name('songLikeDislike');
    Route::post('/group-like-dislike', [ArtistFrontController::class, 'groupLikeDislike'])->name('groupLikeDislike');
    // Fan
    Route::get('/edit-fan-profile', [FanFrontController::class, 'editProfile'])->name('editProfileFan');
    Route::post('/updateFanProfile', [FanFrontController::class, 'updateProfile']);

    Route::get('/add-to-playlist/{songId}', [FanFrontController::class, 'showAddToPlaylist'])->name('showAddToPlayList');
    Route::post('/add-to-playlist', [FanFrontController::class, 'addToPlaylist']);
    Route::post('/create-playlist-and-add-song', [FanFrontController::class, 'createPlaylistAndAddSong']);
    Route::get('/remove-from-playlist/{slug}/{playlistSongId}', [FanFrontController::class, 'showRemoveFromPlaylist'])->name('showRemoveFromPlaylist');
    Route::post('/remove-from-playlist', [FanFrontController::class, 'removeFromPlaylist']);

    Route::get('/edit-fan-playlist/{fanPlaylistId}', [FanFrontController::class, 'showEditFanPlaylist'])->name('showEditFanPlaylist');
    Route::post('/update-fan-playlist', [FanFrontController::class, 'updateFanPlaylist'])->name('updateFanPlaylist');
    Route::post('/fan-search-tag-remove', [HomeController::class, 'fanSearchTagRemove'])->name('fanSearchTagRemove');
    
    Route::get('/my-artists/{search?}', [ArtistFrontController::class, 'myArtists'])->name('myArtists');

    // artist
    // Route::get('/artist/{slug}', [ArtistFrontController::class, 'index'])->name('artistDetail');

    Route::get('/my-playlist/{slug}', [FanFrontController::class, 'myPlaylist'])->name('my-playlist');
    Route::get('/my-favourite/{search?}', [FanFrontController::class, 'myFavourite'])->name('my-favourite');
    Route::get('/recent-played/{search?}', [FanFrontController::class, 'recentPlayed'])->name('recentPlayed');
    Route::get('/favourite-playlist/{search?}', [FanFrontController::class, 'favouritePlaylist'])->name('favourite-playlist');
    Route::get('/myplaylist/{search?}', [FanFrontController::class, 'myPlaylistIndex'])->name('myplaylist');
    Route::get('/my-music', [SongsFrontController::class, 'myMusic'])->name('fanMyMusic');

    // My Reviews
    Route::get('/my-reviews', [MyReviewsFrontController::class, 'index'])->name('myReviewsFan');
    Route::any('/my-reviews/review-delete/', [MyReviewsFrontController::class, 'delete'])->name('reviewDelete');;
    Route::get('/my-reviews/review-edit/{id}', [MyReviewsFrontController::class, 'edit'])->name('fanReviewEdit');
    Route::post('/my-reviews/review-update', [MyReviewsFrontController::class, 'update'])->name('fanReviewUpdate');
    Route::post('/reviews-loadMore', [MyReviewsFrontController::class, 'ajaxReviews'])->name('reviewLoadMore');
    Route::get('/add-review/{artistId?}/{songId?}', [MyReviewsFrontController::class, 'showAddReview'])->name('showAddReview');
    Route::post('/add-review', [MyReviewsFrontController::class, 'AddReview']);

    

    Route::get('/my-subscription', [FanFrontController::class, 'mySubscription'])->name('mySubscription');
    Route::any('/upgrade-subscription', [HomeController::class, 'upgradeSubscription']);
    Route::any('/cancel-subscription', [HomeController::class, 'cancelSubscription']);
    // Route::post('/my-music-player', [FanFrontController::class, 'myMusicPlayer'])->name('myMusicPlayer');
    // Route::post('/get-music-player-data', [FanFrontController::class, 'getMusicPlayerData'])->name('getMusicPlayerData');
    // Route::post('/get-music-player-song-data', [FanFrontController::class, 'getMusicPlayerSongData'])->name('getMusicPlayerSongData');
    // Route::post('/get-music-player-review-data', [FanFrontController::class, 'getMusicPlayerReviewData'])->name('getMusicPlayerReviewData');

    Route::post('/song-increase-view', [SongsFrontController::class, 'songsIncreaseView'])->name('songsIncreaseView');
    Route::post('/song-increase-stream', [SongsFrontController::class, 'SongsIncreaseStream'])->name('SongsIncreaseStream');
    Route::post('/song-add-recent', [SongsFrontController::class, 'SongsAddToRecent'])->name('SongsAddToRecent');
});
