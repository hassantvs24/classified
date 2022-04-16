<?php

use App\Http\Controllers\Advertisement\AdMessageController;
use App\Http\Controllers\Advertisement\AdsGalleyController;
use App\Http\Controllers\Advertisement\SearchSaveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerProfile;
use App\Http\Controllers\Customer\SubscribeController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Package\PackageController;
use App\Http\Controllers\Package\PackagePurchaseController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\TagController;
use App\Http\Controllers\Product\TypesController;
use App\Http\Controllers\Product\BrandController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Advertisement\AdPackageController;
use App\Http\Controllers\Advertisement\AdController;
use App\Http\Controllers\Advertisement\AdReviewController;
use App\Http\Controllers\Advertisement\AdFavouriteController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Product\AttributeController;
use App\Http\Controllers\Product\AttributeSetController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
//use App\Mail\TestMail;
//use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;


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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/', [AdController::class, 'searches']);
Route::post('/customer/reg/', [CustomerController::class, 'customer_register']);

Route::post('/customer/register', [UserController::class, 'register']);

Route::get('/global/product/category', [CategoryController::class, 'index']);
Route::get('/global/product/category/{id}', [CategoryController::class, 'show']);
Route::get('/global/product/category/tree', [CategoryController::class, 'category_tree']);

Route::get('/global/product/brand', [BrandController::class, 'index']);
Route::get('/global/product/brand/{id}', [BrandController::class, 'show']);

Route::get('/global/product/tag', [TagController::class, 'index']);
Route::get('/global/product/tag/{id}', [TagController::class, 'show']);

Route::get('/global/product/types', [TypesController::class, 'index']);
Route::get('/global/product/types/{id}', [TypesController::class, 'show']);

Route::middleware(['auth:api'])->group(function () {

    Route::resource('/product/attribute', AttributeController::class);
    Route::resource('/product/attribute-set', AttributeSetController::class);
    Route::resource('/product/brand', BrandController::class);
    Route::resource('/product/types', TypesController::class);
    Route::get('/product/category/tree', [CategoryController::class, 'category_tree']);
    Route::resource('/product/category', CategoryController::class);
    Route::resource('/product/tag', TagController::class);
    Route::resource('/area', AreaController::class);

   Route::resource('/ads/package', AdPackageController::class);
    Route::get('/ads/message', [AdMessageController::class, 'index']);
    Route::get('/ads/message/{ads_id}', [AdMessageController::class, 'message']);
    Route::post('/ads/message', [AdMessageController::class, 'store']);
    Route::delete('/ads/message/{id}', [AdMessageController::class, 'destroy']);

    Route::resource('/ads/gallery', AdsGalleyController::class);
    Route::resource('/ads', AdController::class);
    Route::post('/ads/status', [AdController::class, 'statusUpdate']);
    Route::resource('/save-search', SearchSaveController::class);

    Route::get('/package/purchase-history', [PackagePurchaseController::class, 'payment_history']);
    Route::get('/package/my-purchase', [PackagePurchaseController::class, 'my_order']);
    Route::resource('/package/purchase', PackagePurchaseController::class);
    Route::resource('/package', PackageController::class);

    Route::resource('/ads.reviews', AdReviewController::class);

    Route::resource('/product', ProductController::class);
    Route::resource('/coupons', CouponController::class);

    /**
     * Customer Profile
     */
    Route::resource('/customer/favourite', AdFavouriteController::class);
    Route::resource('/customer/companies', CompanyController::class);

    Route::get('/customer/profile', [CustomerProfile::class, 'index']);
    Route::get('/customer/subscriber', [CustomerProfile::class, 'subscriber']);//subscriber list
    Route::get('/customer/my-subscribe', [CustomerProfile::class, 'my_subscription']);//subscribe by me
    Route::get('/customer/favorite-ads', [CustomerProfile::class, 'favorite_ads']);
    Route::get('/customer/send-message', [CustomerProfile::class, 'send_message']);
    Route::get('/customer/message', [CustomerProfile::class, 'message']);
    /**
     * /Customer Profile
     */

    Route::post('/customer/status', [CustomerController::class, 'change_status']);
    Route::resource('/customer/subscribe', SubscribeController::class);
    Route::resource('/customer', CustomerController::class);

    Route::put('/permissions/{id}', [RoleController::class, 'assign_role']);
    Route::get('/permissions', [RoleController::class, 'all_permissions']);
    Route::resource('/roles', RoleController::class);

    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::post('/users', [UserController::class, 'save']);
});

/**
 * API Auth
 */
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::get('/auth/me', [AuthController::class, 'me']);
/**
 * /API Auth
 */

/**
 * API Forgot Password
 */
Route::post('password/email', [ForgotPasswordController::class, 'forgot']);
Route::get('password/reset', [ForgotPasswordController::class, 'get_reset'])->name('password.reset');
Route::post('password/reset', [ForgotPasswordController::class, 'reset']);
/**
 * API Forgot Password
 */

/**
 * /Email Verification
 */

Route::post('email/verification-notification', [VerificationController::class, 'sendVerificationEmail'])->name('verification.resend')->middleware('auth:api','verified');
Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

/**
 * /Email Verification
 */


/*
    get Active ADS
 */


Route::get('getActiveAds', [AdController::class, 'getByActiveAds'])->name('activeads');