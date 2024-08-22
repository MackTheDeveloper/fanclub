<?php

use App\Http\Controllers\Admin\DemoController;
use App\Http\Controllers\Admin\demoHowItWorksController;
use App\Http\Controllers\Admin\EmojisAndCommentsController;
use App\Http\Controllers\Admin\FanControllerdemo;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FaqTagsController;
use App\Http\Controllers\Admin\FooterNewController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\HomePageBannerController;
use App\Http\Controllers\Admin\HowItWorksAppController;
use App\Http\Controllers\Admin\ReviewsRatingsController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\SecurityQuestionController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Route;

//Controller
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AttributeGroupController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ManufacturerController;
// use App\Http\Controllers\Photographer\PhotographerController;
use App\Http\Controllers\Photographer\DashboardController as PhotographerDashboard;
use App\Http\Controllers\FrontEnd\HomeController as DecoratoHomeController;
use App\Http\Controllers\FrontEnd\CustomerDashboardController;
use App\Http\Controllers\Admin\EmailTemplatesController;
use App\Http\Controllers\Admin\BlogCategoriesController;
use App\Http\Controllers\Admin\BlogsController;
use App\Http\Controllers\FrontEnd\FrontBlogsController;
use App\Http\Controllers\Admin\BlogCommentsController;
use App\Http\Controllers\Admin\ProductCategoriesController;
use App\Http\Controllers\Admin\CmsPagesController;
use App\Http\Controllers\Admin\ContactUsController;

use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ProfessionalController;

// Music Management
use App\Http\Controllers\Admin\MusicCategoriesController;
use App\Http\Controllers\Admin\MusicGenresController;
use App\Http\Controllers\Admin\MusicLanguagesController;
use App\Http\Controllers\Admin\SongsController;
use App\Http\Controllers\Admin\SongsCommentController;
use App\Http\Controllers\Admin\PlaylistController;

// CMS Management
use App\Http\Controllers\Admin\HowItWorksController;
use App\Http\Controllers\Admin\ForumsController;

// Users Management
use App\Http\Controllers\Admin\FanController;
use App\Http\Controllers\Admin\ArtistController;

// Home page management
use App\Http\Controllers\Admin\DynamicGroupsController;
use App\Http\Controllers\Admin\HomePageComponentController;
use App\Http\Controllers\Admin\LandingInterestsController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
// Frontend Controllers
use App\Http\Controllers\FrontEnd\AccountController;

//Middleware
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PhotographerMiddleware;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\VerifiedUser;
use App\Http\Middleware\PreventRouteAccessMiddleware;
use App\Models\Subscription;

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

//Frontend
// Route::get('/', [DecoratoHomeController::class, 'home'])->name('home');
// Route::get('/login', ['as' => 'login', DecoratoHomeController::class, 'showLogin'])->name('login');
// Route::post('/login', [DecoratoHomeController::class, 'login']);
// // Route::post('login', [ 'as' => 'login', 'uses' => 'LoginController@do']);
// // Route::post('/login', [DecoratoHomeController::class, 'customerLogin']);
// Route::get('/signup', [DecoratoHomeController::class, 'showSignup'])->name('signup');
// Route::post('/signup', [DecoratoHomeController::class, 'signup']);
// // verifyUser
// Route::get('/verify-user', [DecoratoHomeController::class, 'showAuthentication'])->name('verifyUser');
// Route::post('/verify-user', [DecoratoHomeController::class, 'verifyUser'])->name('postVerifyUser');

// Route::get('/forgot-password', [DecoratoHomeController::class, 'showForgotPassForm'])->name('showForgotPassForm');
// Route::post('/verify-otp', [DecoratoHomeController::class, 'forgotPassword'])->name('verifyOTP');
// Route::post('/resend-otp', [DecoratoHomeController::class, 'resendOTP'])->name('resendOTP');
// Route::post('/reset-password', [DecoratoHomeController::class, 'resetPassword'])->name('resetPassword');
// Route::post('/check-otp', [DecoratoHomeController::class, 'checkOTP'])->name('checkOTP');

// // LOGIN WITH OTP
// Route::post('/otp-login/{type?}', [DecoratoHomeController::class, 'showLoginWithOTP'])->name('showLoginWithOTP');
// Route::post('/verify-otp-login', [DecoratoHomeController::class, 'postLoginWithOTP'])->name('postLoginWithOTP');

// Route::post('/submit-reset-password', [DecoratoHomeController::class, 'resetPasswordPost'])->name('postResetPassword');
// Route::get('/reset-password-success', [DecoratoHomeController::class, 'resetPasswordSuccess'])->name('resetPasswordSuccess');
// Route::get('/verification-success/{code}', [DecoratoHomeController::class, 'emailVerificationSuccess']);
// Route::get('/reset-password/{token}', [DecoratoHomeController::class, 'showResetPassForm']);
// // Route::post('/reset-password', [DecoratoHomeController::class, 'resetPassword']);

// Route::get('oauth/{provider}', [DecoratoHomeController::class, 'redirect']);
// Route::get('oauth/{provider?}/callback', [DecoratoHomeController::class, 'socialLogin']);
// // Route::get('oauth/{provider?}/callback', [DecoratoHomeController::class, 'customerLogin']);

// Route::get('/getLocaleDetailsForLang', [CustomerDashboardController::class, 'getLocalDetailsForLang']);
// Route::get('/getEmailTemplatesForLang', [CustomerDashboardController::class, 'getEmailTemplateForLang']);

// // Route::prefix('customer')->middleware([CustomerMiddleware::class])->group(function () {
// //     Route::get('/logout', [DecoratoHomeController::class, 'logout']);
// //     Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard']);
// // });

// //Clear Cache facade value:

// Route::get('/clear-cache', function () {
//     $exitCode = Artisan::call('cache:clear');
//     return '<h1>Cache facade value cleared</h1>';
// });

// //Reoptimized class loader:
// Route::get('/optimize', function () {
//     $exitCode = Artisan::call('optimize');
//     return '<h1>Reoptimized class loader</h1>';
// });

// //Route cache:
// Route::get('/route-cache', function () {
//     $exitCode = Artisan::call('route:cache');
//     return '<h1>Routes cached</h1>';
// });

// //Clear Route cache:
// Route::get('/route-clear', function () {
//     $exitCode = Artisan::call('route:clear');
//     return '<h1>Route cache cleared</h1>';
// });

// //Clear View cache:
// Route::get('/view-clear', function () {
//     $exitCode = Artisan::call('view:clear');
//     return '<h1>View cache cleared</h1>';
// });

// //Clear Config cache:
// Route::get('/config-cache', function () {
//     $exitCode = Artisan::call('config:cache');
//     return '<h1>Clear Config cleared</h1>';
// });

// Route::get('brands', [ManufacturerController::class, 'getBrands']);

// Admin Group
// Route::prefix('admin')->middleware([AdminMiddleware::class])->group(function () {
Route::prefix('securefcbcontrol')->middleware([AdminMiddleware::class])->group(function () {

    // Login Routes...
    Route::get('login', [AdminController::class, 'showLoginForm'])->withoutMiddleware([AdminMiddleware::class]);
    Route::post('login', [AdminController::class, 'login'])->withoutMiddleware([AdminMiddleware::class]);
    Route::get('/logout', [AdminController::class, 'logout']);
    Route::get('/toggleSidebar', [AdminController::class, 'toggleSidebar']);

    //Admin Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard']);
    Route::get('dashboard/listprofessionalrequest', [DashboardController::class, 'listprofessionalrequest']);
    Route::get('dashboard/monthly-graph/{duration}', [DashboardController::class, 'monthlyGraph']);
    Route::get('dashboard/review-graph', [DashboardController::class, 'reviewGraph']);
    Route::post('dashboard/dashboard-filter', [DashboardController::class, 'dashboardFilter']);
    Route::post('dashboard/serach-dashboard', [DashboardController::class, 'serachDashboard'])->name('serachDashboard');
    // delete professional request
    Route::post('dashboard/artist-delete', [DashboardController::class, 'deleteArtist'])->name('deleteArtist');


    //Roles & Permissions Routes
    Route::get('user/role/add', [RoleController::class, 'getRoleForm'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('user/role/add', [RoleController::class, 'addRole']);
    Route::get('user/role/list', [RoleController::class, 'getListOfRoles'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('role/permissions/{id}', [RoleController::class, 'getPermissions'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('role/permissions/{id}', [RoleController::class, 'getPermissions']);
    Route::get('user/role/edit/{id}', [RoleController::class, 'editRole'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('user/role/update', [RoleController::class, 'updateRole']);
    Route::post('user/role/delete', [RoleController::class, 'deleteRole'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('user/search_role', [RoleController::class, 'searchRole']);


    //Users Routes
    Route::get('user/list', [UserController::class, 'getUserList'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('user/add', [UserController::class, 'getUserForm'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('user/add', [UserController::class, 'addUser']);
    Route::get('user/edit/{id}', [UserController::class, 'editUser'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('user/update', [UserController::class, 'updateUser']);
    Route::get('user/export', [UserController::class, 'exportUsers'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('user/import', [UserController::class, 'getimportUsersForm'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('user/import', [UserController::class, 'importUser']);
    Route::get('user/{id}/delete', [UserController::class, 'deleteUser'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('user/activate-deactivate', [UserController::class, 'userActDeaAct'])->middleware([PreventRouteAccessMiddleware::class]);
    // User Permussion
    Route::get('user/permissions/{id}', [UserController::class, 'getPermissions'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('user/permissions/{id}', [UserController::class, 'getPermissions']);

    //Registered Users Routes
    // Route::get('registeredUser/list', [UserController::class, 'getRegisteredUserList']);
    // Route::get('registeredUser/add', [UserController::class, 'getRegisteredUserForm']);
    Route::get('fan/list', [UserController::class, 'getRegisteredUserList'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('fan/add', [UserController::class, 'getRegisteredUserForm'])->middleware([PreventRouteAccessMiddleware::class])->name('fan.add');
    Route::post('fan/add', [UserController::class, 'addRegisteredUser']);
    Route::get('fan/edit/{id}', [UserController::class, 'editRegisteredUser'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('fan/update', [UserController::class, 'updateRegisteredUser']);
    // Route::get('registeredUser/{id}/delete', [UserController::class, 'deleteUser']);
    // Route::post('registeredUser/activate-deactivate', [UserController::class, 'userActDeaAct']);
    Route::get('fan/{id}/delete', [UserController::class, 'deleteUser'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('fan/activate-deactivate', [UserController::class, 'userActDeaAct'])->middleware([PreventRouteAccessMiddleware::class]);


    //Professional Routes
    // Route::get('artist/list', [ProfessionalController::class, 'getProfessionalList']);
    // Route::get('artist/add', [ProfessionalController::class, 'getUserForm']);
    // Route::post('artist/add', [ProfessionalController::class, 'addUser']);
    // Route::get('artist/edit/{id}', [ProfessionalController::class, 'editUser']);
    // Route::post('artist/update', [ProfessionalController::class, 'updateUser']);
    // Route::get('artist/{id}/delete', [ProfessionalController::class, 'deleteUser']);
    // Route::post('artist/activate-deactivate', [ProfessionalController::class, 'userActDeaAct']);
    // Route::post('artist/subscribe-unsubscribe', [ProfessionalController::class, 'userSubUnsub']);
    // Route::get('artist/designs/{id}',[ProfessionalController::class, 'designView']);
    // Route::post('artist/designs/{id}',[ProfessionalController::class, 'designStore']);
    // Route::post('artist/designs/{id}/delete',[ProfessionalController::class, 'designDelete']);
    // Route::post('artist/bycateory',[ProfessionalController::class, 'byCategory']);
    // Route::get('artist/import', [ProfessionalController::class, 'getimportProfesisonalForm']);
    // Route::post('artist/import', [ProfessionalController::class, 'importProfessional']);
    // Route::post('artist/areabycity', [ProfessionalController::class, 'getAreaByCity'])->name('getAreaByCity');

    //Dynamic Groups Routes
    Route::get('dynamic-groups/index', [DynamicGroupsController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('dynamic-groups/list', [DynamicGroupsController::class, 'list']);
    Route::get('dynamic-groups/create', [DynamicGroupsController::class, 'create'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('dynamic-groups/store', [DynamicGroupsController::class, 'store']);
    Route::get('dynamic-groups/edit/{id}', [DynamicGroupsController::class, 'edit'])->name('editGroup')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('dynamic-groups/update/{id}', [DynamicGroupsController::class, 'update']);
    Route::get('dynamic-groups/groupdatalist', [DynamicGroupsController::class, 'getGroupDataList']);
    Route::post('dynamic-groups/addbulkitems', [DynamicGroupsController::class, 'addBulkItems']);
    Route::post('dynamic-groups/removebulkitems', [DynamicGroupsController::class, 'removeBulkItems']);
    Route::post('dynamic-groups/additems', [DynamicGroupsController::class, 'addItems']);
    Route::post('dynamic-groups/removeitems', [DynamicGroupsController::class, 'removeItems']);
    Route::post('dynamic-groups/activeInactive', [DynamicGroupsController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('dynamic-groups/delete/{id}', [DynamicGroupsController::class, 'delete'])->name('deleteGroup')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('dynamic-groups/getGroupDetails', [DynamicGroupsController::class, 'getGroupDetails']);
    Route::post('dynamic-groups/getUtlTypeDetails', [DynamicGroupsController::class, 'getUtlTypeDetails']);

    //Home page component Routes
    Route::get('homepage-component/index', [HomePageComponentController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('homepage-component/list', [HomePageComponentController::class, 'list']);
    Route::get('homepage-component/create', [HomePageComponentController::class, 'create'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('homepage-component/store', [HomePageComponentController::class, 'store']);
    Route::post('homepage-component/upload-home-page-image', [HomePageComponentController::class, 'uploadHomePageImage'])->name('ckeditor.upload_home_page_image');
    Route::get('homepage-component/edit/{id}', [HomePageComponentController::class, 'edit'])->name('editHomePageComponent')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('homepage-component/update/{id}', [HomePageComponentController::class, 'update']);
    Route::post('homepage-component/activeInactive', [HomePageComponentController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('homepage-component/delete/{id}', [HomePageComponentController::class, 'delete'])->name('delete')->middleware([PreventRouteAccessMiddleware::class]);

    //Playlist Routes
    Route::get('playlists/index', [PlaylistController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('playlists/list', [PlaylistController::class, 'list']);
    Route::get('playlists/create', [PlaylistController::class, 'create'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('playlists/store', [PlaylistController::class, 'store']);
    Route::get('playlists/edit/{id}', [PlaylistController::class, 'edit'])->name('editPlaylist')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('playlists/update/{id}', [PlaylistController::class, 'update']);
    Route::post('playlists/activeInactive', [PlaylistController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('playlists/delete/{id}', [PlaylistController::class, 'delete'])->name('delete')->middleware([PreventRouteAccessMiddleware::class]);


    // Events
    Route::get('event/list', [EventsController::class, 'getListOfEvents']);
    Route::get('event/addEvent', [EventsController::class, 'eventsAddView']);
    Route::post('event/addEvent', [EventsController::class, 'addEvent']);
    Route::get('event/editEvent/{id}', [EventsController::class, 'eventEditView']);
    Route::post('event/updateEvent', [EventsController::class, 'updateEvent']);
    Route::post('event/activeDeactiveEvent', [EventsController::class, 'eventActiveInactive']);
    Route::post('event/{id}/deleteEvent', [EventsController::class, 'deleteEvent']);
    Route::get('event/exportEvent', [EventsController::class, 'getExportEvents']);

    //Customer Routes
    Route::get('customer/list', [CustomerController::class, 'getCustomerList']);
    Route::get('customer/edit/{id}', [CustomerController::class, 'editCustomer']);
    Route::post('customer/update', [CustomerController::class, 'updateAccountCustomer']);
    Route::get('customer/export', [CustomerController::class, 'exportCustomer']);
    Route::get('customer/import', [CustomerController::class, 'getimportCustomerForm']);
    Route::post('customer/import', [CustomerController::class, 'importCustomer']);
    Route::get('customer/{id}/delete', [CustomerController::class, 'deleteCustomer']);
    Route::post('customer/activate-deactivate', [CustomerController::class, 'customerActDeaAct']);
    Route::get('customer/states/{id}', [CustomerController::class, 'getStates']);
    Route::get('customer/cities/{id}', [CustomerController::class, 'getCities']);
    Route::post('customer/address', [CustomerController::class, 'addCustomerAddress']);
    Route::get('customer/address/edit/{id}', [CustomerController::class, 'editCustAddress']);
    Route::post('customer/address/update', [CustomerController::class, 'updateCustomerAddress']);
    Route::get('customer/address/delete/{id}', [CustomerController::class, 'deleteCustAddress']);

    //Currency
    Route::get('currency/list', [AdminController::class, 'listCurrency']);
    Route::get('currency/add', [AdminController::class, 'showAddCurrForm']);
    Route::post('currency/add', [AdminController::class, 'addCurrency']);
    Route::get('currency/edit/{id}', [AdminController::class, 'editCurrency']);
    Route::post('currency/update', [AdminController::class, 'updateCurrency']);
    Route::post('currency/delete', [AdminController::class, 'deleteCurrency']);
    Route::post('currency/default', [AdminController::class, 'defaultCurrency']);

    //Language
    Route::get('language/add', [AdminController::class, 'showAddLanguageForm']);
    Route::post('language/add', [AdminController::class, 'addLanguage']);
    Route::get('language/list', [AdminController::class, 'listLanguage']);
    Route::get('language/edit/{id}', [AdminController::class, 'editLanguage']);
    Route::post('language/update', [AdminController::class, 'updateLanguage']);
    Route::post('language/delete', [AdminController::class, 'deleteLanguage']);
    Route::post('language/default', [AdminController::class, 'defaultLanguage']);


    //Profile
    Route::get('/profile', [AdminController::class, 'profile']);
    Route::post('/update-profile', [AdminController::class, 'updateProfile']);

    //Forgot Password
    Route::get('/forgot-password', [AdminController::class, 'showForgotPassForm'])->withoutMiddleware([AdminMiddleware::class]);
    Route::post('/forgot-password', [AdminController::class, 'forgotPassword'])->withoutMiddleware([AdminMiddleware::class]);

    Route::get('/reset-password/{token}', [AdminController::class, 'showResetPassForm'])->withoutMiddleware([AdminMiddleware::class]);
    Route::post('/reset-password', [AdminController::class, 'resetPassword'])->withoutMiddleware([AdminMiddleware::class]);

    //Change Password
    Route::get('/change/password', [AdminController::class, 'changePasswordForm']);
    Route::post('/change/password', [AdminController::class, 'changePassword']);

    //Contact Us backend
    Route::get('/contactUs', [ContactUsController::class, 'getContactUs'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('/contactUs/contactUsData', [ContactUsController::class, 'getContactUsData']);
    Route::get('/contactUs/inquiry', [ContactUsController::class, 'getContactUsInquiry']);
    Route::post('/contactUs/reply', [ContactUsController::class, 'postContactUsReply'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('/contactUs/deleteInquiry', [ContactUsController::class, 'postDeleteInquiry'])->middleware([PreventRouteAccessMiddleware::class]);


    // Settings - Footer
    Route::get('/footerDetails', [SettingsController::class, 'footerDetailsView']);
    Route::post('/updateFooterDetails', [SettingsController::class, 'updateFooterDetails']);

    // Settings
    Route::get('/settings', [SettingsController::class, 'getSetting']);
    Route::post('/settings', [SettingsController::class, 'setSetting']);

    // CMS Pages
    Route::any('/cms-page/list', [CmsPagesController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class])->name('cmsPageListing');
    Route::post('/cms-page/cmsPageActiveInactive', [CmsPagesController::class, 'cmsPageActiveInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('/cms-page/create', [CmsPagesController::class, 'create'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('/cms-page/store', [CmsPagesController::class, 'store']);
    Route::get('/cms-page/edit/{id}', [CmsPagesController::class, 'edit'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('/cms-page/update/{id}', [CmsPagesController::class, 'update']);
    Route::post('/cms-page/delete/{id}', [CmsPagesController::class, 'delete'])->middleware([PreventRouteAccessMiddleware::class]);

    // Footer
    Route::get('/footer/list', [FooterController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('/footer/cmsPageActiveInactive', [FooterController::class, 'cmsPageActiveInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('/footer/create', [FooterController::class, 'create'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('/footer/store', [FooterController::class, 'store']);
    Route::get('/footer/edit/{id}', [FooterController::class, 'edit'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('/footer/update/{id}', [FooterController::class, 'update']);
    Route::post('/footer/delete/{id}', [FooterController::class, 'delete'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('/footer/updateData', [FooterController::class, 'getType']);

    // Subscription
    Route::any('subscriptions/index', [SubscriptionController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class])->name('subscriptionsListing');
    Route::any('subscriptions/list', [SubscriptionController::class, 'list'])->name('sublist');
    Route::any('subscriptions/export', [SubscriptionController::class, 'exportSubscription'])->name('exportSub');

    // Transaction
    Route::any('transaction/index', [TransactionController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class])->name('transactionListing');
    Route::any('transaction/list', [TransactionController::class, 'list'])->name('transactionlist');
    Route::any('transaction/export', [TransactionController::class, 'exportTransaction'])->name('exportTransaction');


    // Reviews & Ratings
    Route::get('reviews-ratings/index', [ReviewsRatingsController::class, 'index'])->name('forumsList')->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('reviews-ratings/list', [ReviewsRatingsController::class, 'list']);
    Route::post('reviews-ratings/delete/{id}', [ReviewsRatingsController::class, 'delete'])->name('forumsDelete')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('reviews-ratings/activeInactive', [ReviewsRatingsController::class, 'activeInactive'])->name('forumsStatus')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('reviews-ratings/demolist', [ReviewsRatingsController::class, 'demolist']);


    // How It Works App
    Route::get('how-it-works-app/index', [HowItWorksController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('how-it-works-app/list', [HowItWorksController::class, 'list']);
    Route::post('/how-it-works-app/cmsPageActiveInactive', [HowItWorksAppController::class, 'cmsPageActiveInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('/how-it-works-app/create', [HowItWorksAppController::class, 'create'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('/how-it-works-app/store', [HowItWorksAppController::class, 'store']);
    Route::get('/how-it-works-app/edit/{id}', [HowItWorksAppController::class, 'edit'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('/how-it-works-app/update/{id}', [HowItWorksAppController::class, 'update']);
    Route::post('/how-it-works-app/delete/{id}', [HowItWorksAppController::class, 'delete'])->middleware([PreventRouteAccessMiddleware::class]);




    // Email Temlates
    Route::get('email-templates/index', [EmailTemplatesController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('email-templates/list', [EmailTemplatesController::class, 'list']);
    Route::any('email-templates/add', [EmailTemplatesController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('email-templates/store', [EmailTemplatesController::class, 'store']);
    Route::post('email-templates/activeInactive', [EmailTemplatesController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('email-templates/delete/{id}', [EmailTemplatesController::class, 'delete'])->name('deleteEmailTemplate')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('email-templates/edit/{id}', [EmailTemplatesController::class, 'edit'])->name('editEmailTemplate')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('email-templates/update/{id}', [EmailTemplatesController::class, 'update']);
    Route::post('email-templates/upload-image', [EmailTemplatesController::class, 'uploadEmailImage'])->name('ckeditor.upload_email_image');

    // Blog Category
    Route::get('songs-category/index', [BlogCategoriesController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('songs-category/list', [BlogCategoriesController::class, 'list']);
    Route::any('songs-category/add', [BlogCategoriesController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('songs-category/store', [BlogCategoriesController::class, 'store']);
    Route::post('songs-category/activeInactive', [BlogCategoriesController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('songs-category/delete/{id}', [BlogCategoriesController::class, 'delete'])->name('deleteBlogCategory')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('songs-category/edit/{id}', [BlogCategoriesController::class, 'edit'])->name('editBlogCategory')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('songs-category/update/{id}', [BlogCategoriesController::class, 'update']);

    // Blog
    // Route::get('songs/index', [BlogsController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    // Route::any('songs/list', [BlogsController::class, 'list']);
    // Route::any('songs/add', [BlogsController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    // Route::post('songs/store', [BlogsController::class, 'store']);
    // Route::post('songs/activeInactive', [BlogsController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    // Route::post('songs/delete/{id}', [BlogsController::class, 'delete'])->name('deleteBlog')->middleware([PreventRouteAccessMiddleware::class]);
    // Route::get('songs/edit/{id}', [BlogsController::class, 'edit'])->name('editBlog')->middleware([PreventRouteAccessMiddleware::class]);
    // Route::post('songs/update/{id}', [BlogsController::class, 'update']);

    // Blog Comments
    Route::get('blog-comment/index/{blogID?}', [BlogCommentsController::class, 'index'])->name('indexBlogComment')->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('blog-comment/list', [BlogCommentsController::class, 'list'])->name('listBlogComment');
    Route::post('blog-comment/activeInactive', [BlogCommentsController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('blog-comment/delete/{id}', [BlogCommentsController::class, 'delete'])->name('deleteBlogComment')->middleware([PreventRouteAccessMiddleware::class]);


    // Music Category
    Route::get('music-categories/index', [MusicCategoriesController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('music-categories/list', [MusicCategoriesController::class, 'list']);
    Route::any('music-categories/add', [MusicCategoriesController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('music-categories/store', [MusicCategoriesController::class, 'store']);
    Route::post('music-categories/activeInactive', [MusicCategoriesController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('music-categories/delete/{id}', [MusicCategoriesController::class, 'delete'])->name('deleteMusicCategory')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('music-categories/edit/{id}', [MusicCategoriesController::class, 'edit'])->name('editMusicCategory')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('music-categories/update/{id}', [MusicCategoriesController::class, 'update']);

    // Music Genre
    Route::get('music-genres/index', [MusicGenresController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('music-genres/list', [MusicGenresController::class, 'list']);
    Route::any('music-genres/add', [MusicGenresController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('music-genres/store', [MusicGenresController::class, 'store']);
    Route::post('music-genres/activeInactive', [MusicGenresController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('music-genres/delete/{id}', [MusicGenresController::class, 'delete'])->name('deleteMusicGenre')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('music-genres/edit/{id}', [MusicGenresController::class, 'edit'])->name('editMusicGenre')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('music-genres/update/{id}', [MusicGenresController::class, 'update']);

    // HomePage banner
    Route::get('homepagebanner/index', [HomePageBannerController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('homepagebanner/list', [HomePageBannerController::class, 'list']);
    Route::any('homepagebanner/add', [HomePageBannerController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('homepagebanner/store', [HomePageBannerController::class, 'store']);
    Route::post('homepagebanner/activeInactive', [HomePageBannerController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('homepagebanner/delete/{id}', [HomePageBannerController::class, 'delete'])->name('deleteHPB')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('homepagebanner/edit/{id}', [HomePageBannerController::class, 'edit'])->name('editHPB')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('homepagebanner/update/{id}', [HomePageBannerController::class, 'update']);
    Route::post('/homepagebanner/typeChange', [HomePageBannerController::class, 'getType']);
    Route::post('/homepagebanner/existData', [HomePageBannerController::class, 'existType'])->name('existData');





    // Music Language
    Route::get('music-languages/index', [MusicLanguagesController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('music-languages/list', [MusicLanguagesController::class, 'list']);
    Route::any('music-languages/add', [MusicLanguagesController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('music-languages/store', [MusicLanguagesController::class, 'store']);
    Route::post('music-languages/activeInactive', [MusicLanguagesController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('music-languages/delete/{id}', [MusicLanguagesController::class, 'delete'])->name('deleteMusicLanguage')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('music-languages/edit/{id}', [MusicLanguagesController::class, 'edit'])->name('editMusicLanguage')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('music-languages/update/{id}', [MusicLanguagesController::class, 'update']);



    // Fans
    Route::any('fans/index', [FanController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class])->name('fanListing');
    Route::any('fans/list', [FanController::class, 'list']);
    Route::any('fans/add', [FanController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('fans/store', [FanController::class, 'store']);
    Route::post('fans/export', [FanController::class, 'export'])->name('exportFans')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('fans/activeInactive', [FanController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('fans/delete/{id}', [FanController::class, 'delete'])->name('deleteFan')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('fans/edit/{id}', [FanController::class, 'edit'])->name('editFan')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('fans/playlist/{id}', [FanController::class, 'playlist'])->name('FanPlaylist')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('fans/playlist/activeInactive', [FanController::class, 'activeInactivePlaylist'])->name('activeInactivePlaylist')->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('fans/playlist/delete/{id}', [FanController::class, 'playlistDelete'])->name('playlistDelete')->middleware([PreventRouteAccessMiddleware::class]);
    // Route::get('fans/playlist/delete/{id}', [FanFrontController::class, 'playlistDelete'])->name('FanPlaylistDelete')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('fans/playlist/songs/{id}', [FanController::class, 'playlistSongs'])->name('FanPlaylistSongs')->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('fans/playlist/songs/delete/{id}', [FanController::class, 'playlistSongsDelete'])->name('FanPlaylistSongsDelete')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('fans/update/{id}', [FanController::class, 'update']);
    Route::any('fans/export', [FanController::class, 'exportFan'])->name('exportFan');
    Route::any('fans/states', [FanController::class, 'getStates'])->name('getStates');



    // Artists
    Route::any('artists/index', [ArtistController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class])->name('artistListing');
    Route::any('artists/list', [ArtistController::class, 'list']);
    Route::any('artists/dashboard-list', [ArtistController::class, 'dashboardList']);
    Route::any('artists/add', [ArtistController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('artists/store', [ArtistController::class, 'store']);
    Route::post('artists/activeInactive', [ArtistController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('artists/viewMap', [ArtistController::class, 'viewMap'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('artists/approve', [ArtistController::class, 'approve'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('artists/delete/{id}', [ArtistController::class, 'delete'])->name('deleteArtist')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('artists/edit/{id}', [ArtistController::class, 'edit'])->name('editArtist')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('artists/update/{id}', [ArtistController::class, 'update']);
    Route::any('artists/export', [ArtistController::class, 'exportArtist'])->name('exportArtist');
    Route::get('artist/events/{id}', [ArtistController::class, 'events'])->name('artistEvents');
    Route::any('artist/events/delete/{id}', [ArtistController::class, 'eventsDelete'])->name('eventsDelete')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('artist/news/{id}', [ArtistController::class, 'news'])->name('artistNews');
    Route::any('artist/news/delete/{id}', [ArtistController::class, 'newsDelete'])->name('newsDelete')->middleware([PreventRouteAccessMiddleware::class]);


    // Songs
    Route::any('songs/index', [SongsController::class, 'index'])->name('songsList')->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('songs/list', [SongsController::class, 'list']);
    Route::any('songs/add', [SongsController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('songs/download/{id}', [SongsController::class, 'download'])->name('songDownload')->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('songs/delete/{id}', [SongsController::class, 'delete'])->name('songdelete')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('songs/store', [SongsController::class, 'store']);


    // Song Comments
    Route::any('song_comments/index', [SongsCommentController::class, 'index'])->name('songComments')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('song_comments/list', [SongsCommentController::class, 'list'])->name('songCommentsList')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('song_comments/get-songs', [SongsCommentController::class, 'getSongList'])->name('songCommentsSongList')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('song_comments/delete/{id}', [SongsCommentController::class, 'delete'])->name('songCommentsDelete')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('song_comments/view/{id}', [SongsCommentController::class, 'getComment'])->name('songCommentsView')->middleware([PreventRouteAccessMiddleware::class]);



    // How It Works
    Route::get('how-it-works-app/index', [HowItWorksAppController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('how-it-works-app/list', [HowItWorksAppController::class, 'list']);
    Route::any('how-it-works-app/add', [HowItWorksAppController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('how-it-works-app/store', [HowItWorksAppController::class, 'store']);
    Route::post('how-it-works-app/activeInactive', [HowItWorksAppController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('how-it-works-app/delete/{id}', [HowItWorksAppController::class, 'delete'])->name('deleteHowItWorks')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('how-it-works-app/edit/{id}', [HowItWorksAppController::class, 'edit'])->name('editHowItWorksApp')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('how-it-works-app/update/{id}', [HowItWorksAppController::class, 'update']);


    // Security Questions
    Route::get('security-questions/index', [SecurityQuestionController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('security-questions/list', [SecurityQuestionController::class, 'list']);
    Route::any('security-questions/add', [SecurityQuestionController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('security-questions/store', [SecurityQuestionController::class, 'store']);
    Route::post('security-questions/activeInactive', [SecurityQuestionController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('security-questions/delete/{id}', [SecurityQuestionController::class, 'delete'])->name('deleteSecurity')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('security-questions/edit/{id}', [SecurityQuestionController::class, 'edit'])->name('editSecurity')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('security-questions/update/{id}', [SecurityQuestionController::class, 'update']);


    // Emojis and Comments
    Route::get('emojis-and-comments/index', [EmojisAndCommentsController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('emojis-and-comments/list', [EmojisAndCommentsController::class, 'list']);
    Route::any('emojis-and-comments/add', [EmojisAndCommentsController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('emojis-and-comments/store', [EmojisAndCommentsController::class, 'store']);
    Route::post('emojis-and-comments/activeInactive', [EmojisAndCommentsController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('emojis-and-comments/delete/{id}', [EmojisAndCommentsController::class, 'delete'])->name('deleteHowItWorks')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('emojis-and-comments/edit/{id}', [EmojisAndCommentsController::class, 'edit'])->name('emojiEdits')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('emojis-and-comments/update/{id}', [EmojisAndCommentsController::class, 'update']);


    // FAQ
    Route::get('faq/index', [FaqController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('faq/list', [FaqController::class, 'list']);
    Route::any('faq/add', [FaqController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('faq/store', [FaqController::class, 'store']);
    Route::post('faq/activeInactive', [FaqController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('faq/delete/{id}', [FaqController::class, 'delete'])->name('deleteFaq')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('faq/edit/{id}', [FaqController::class, 'edit'])->name('faqEdits')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('faq/update/{id}', [FaqController::class, 'update']);

    // Faq Tags
    Route::get('faq-tags/index', [FaqTagsController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('faq-tags/list', [FaqTagsController::class, 'list']);
    Route::any('faq-tags/add', [FaqTagsController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('faq-tags/store', [FaqTagsController::class, 'store']);
    Route::post('faq-tags/activeInactive', [FaqTagsController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('faq-tags/delete/{id}', [FaqTagsController::class, 'delete'])->name('deleteFaqTags')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('faq-tags/edit/{id}', [FaqTagsController::class, 'edit'])->name('editFaqTags')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('faq-tags/update/{id}', [FaqTagsController::class, 'update']);


    // How It Works
    Route::get('how-it-works/index', [HowItWorksController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('how-it-works/list', [HowItWorksController::class, 'list']);
    Route::any('how-it-works/add', [HowItWorksController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('how-it-works/get-sort-order/{type}', [HowItWorksController::class, 'getSortOrder'])->name('howItWorkgetSortOrder');
    Route::post('how-it-works/store', [HowItWorksController::class, 'store']);
    Route::post('how-it-works/activeInactive', [HowItWorksController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('how-it-works/delete/{id}', [HowItWorksController::class, 'delete'])->name('deleteHowItWorks')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('how-it-works/edit/{id}', [HowItWorksController::class, 'edit'])->name('editHowItWorks')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('how-it-works/update/{id}', [HowItWorksController::class, 'update']);

    // Subscription Plans
    Route::get('subscription-plan/index', [SubscriptionPlanController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('subscription-plan/list', [SubscriptionPlanController::class, 'list']);
    Route::get('subscription-plan/edit/{id}', [SubscriptionPlanController::class, 'edit'])->name('editSubscriptionPlan')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('subscription-plan/update/{id}', [SubscriptionPlanController::class, 'update']);

    // footer new
    Route::get('footer-link/index', [FooterNewController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('footer-link/list', [FooterNewController::class, 'list']);
    Route::any('footer-link/add', [FooterNewController::class, 'add'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('footer-link/get-sort-order/{type}', [FooterNewController::class, 'getSortOrder'])->name('howItWorkgetSortOrder');
    Route::post('footer-link/create', [FooterNewController::class, 'store']);
    Route::post('footer-link/activeInactive', [FooterNewController::class, 'activeInactive'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('footer-link/delete/{id}', [FooterNewController::class, 'delete'])->name('deleteFooter')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('footer-link/edit/{id}', [FooterNewController::class, 'edit'])->name('editFooter')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('footer-link/update/{id}', [FooterNewController::class, 'update']);
    Route::post('/footer-link/updateData', [FooterNewController::class, 'getType']);


    // forums
    Route::any('forums/index', [ForumsController::class, 'index'])->name('forumsListing')->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('forums/list', [ForumsController::class, 'list']);
    Route::post('forums/delete/{id}', [ForumsController::class, 'delete'])->name('forumsDelete')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('forums/activeInactive', [ForumsController::class, 'activeInactive'])->name('forumsStatus')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('forums/demolist', [ForumsController::class, 'demolist']);

    // forum Comments
    Route::any('forums/comments/{id}', [ForumsController::class, 'commentindex'])->name('forumComments')->middleware([PreventRouteAccessMiddleware::class]);
    Route::get('forums/comments/list/{id}', [ForumsController::class, 'commentlist'])->name('forumCommentsList')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('forums/comments/delete/{id}', [ForumsController::class, 'commentdelete'])->name('forumCommentsDelete')->middleware([PreventRouteAccessMiddleware::class]);
    Route::post('forums/view', [ForumsController::class, 'commentView'])->name('commentView');
    // Route::post('forums/comments/get-forums', [forumsCommentController::class, 'getforumList'])->name('forumCommentsforumList')->middleware([PreventRouteAccessMiddleware::class]);
    // Route::post('forums/comments/delete/{id}', [forumsCommentController::class, 'delete'])->name('forumCommentsDelete')->middleware([PreventRouteAccessMiddleware::class]);
    // Route::get('forums/comments/view/{id}', [forumsCommentController::class, 'getComment'])->name('forumCommentsView')->middleware([PreventRouteAccessMiddleware::class]);


    // Landing Interest
    Route::get('landing-interest/index', [LandingInterestsController::class, 'index'])->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('landing-interest/list', [LandingInterestsController::class, 'list']);
    Route::post('landing-interest/delete/{id}', [LandingInterestsController::class, 'delete'])->name('deleteLandingInterest')->middleware([PreventRouteAccessMiddleware::class]);
    Route::any('landing-interest/export', [LandingInterestsController::class, 'export'])->name('exportLandingInterest');
});



// Front Logged In Routes
