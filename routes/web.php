<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AdministrativeUnitController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ContentController;
use App\Http\Controllers\Backend\CustomerInquiryController as BackendCustomerInquiryController;
use App\Http\Controllers\Backend\ExpertController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\SeoConfigController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\TourOptionController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Frontend\CustomerInquiryController as FrontendCustomerInquiryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\Frontend\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('frontend.home');
Route::get('/gioi-thieu', [PageController::class, 'about'])->name('frontend.about');
Route::get('/lien-he', [PageController::class, 'contact'])->name('frontend.contact');
Route::get('/san-pham/{slug}', [PageController::class, 'legacyProductShow'])->name('frontend.products.show.legacy');
Route::get('/ajax/danh-muc-tour/{slug}', [PageController::class, 'productCategoryAjax'])->name('frontend.products.filter');
Route::get('/tin-tuc', [PageController::class, 'newsIndex'])->name('frontend.news.index');
Route::get('/tin-tuc/{slug}', [PageController::class, 'legacyNewsShow'])->name('frontend.news.show.legacy');

// Wishlist
Route::get('/tour-yeu-thich', [\App\Http\Controllers\Frontend\WishlistController::class, 'index'])->name('frontend.wishlist.index');
Route::post('/wishlist/toggle', [\App\Http\Controllers\Frontend\WishlistController::class, 'toggle'])->name('frontend.wishlist.toggle');

// Frontend Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('dang-nhap', [AuthController::class, 'showLoginForm'])->name('frontend.login');
    Route::post('dang-nhap', [AuthController::class, 'login'])->name('frontend.login.submit');
    Route::get('dang-ky', [AuthController::class, 'showRegisterForm'])->name('frontend.register');
    Route::post('dang-ky', [AuthController::class, 'register'])->name('frontend.register.submit');
    
    // Google Social Login Routes
    Route::get('dang-nhap/google', [AuthController::class, 'redirectToGoogle'])->name('frontend.login.google');
    Route::get('dang-nhap/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('frontend.login.google.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('dang-xuat', [AuthController::class, 'logout'])->name('frontend.logout');
    Route::get('thong-tin-tai-khoan', [\App\Http\Controllers\Frontend\ProfileController::class, 'index'])->name('frontend.profile');
    Route::get('cai-dat-tai-khoan', [\App\Http\Controllers\Frontend\ProfileController::class, 'settings'])->name('frontend.profile.settings');
    Route::post('cai-dat-tai-khoan/ho-so', [\App\Http\Controllers\Frontend\ProfileController::class, 'updateProfile'])->name('frontend.profile.settings.updateProfile');
    Route::post('cai-dat-tai-khoan/mat-khau', [\App\Http\Controllers\Frontend\ProfileController::class, 'updatePassword'])->name('frontend.profile.settings.updatePassword');
    Route::post('cai-dat-tai-khoan/avatar', [\App\Http\Controllers\Frontend\ProfileController::class, 'updateAvatar'])->name('frontend.profile.settings.updateAvatar');
});

Route::get('/login', function () {
    return redirect()->route('frontend.login');
})->name('login');

Route::post('/customer-inquiries', [FrontendCustomerInquiryController::class, 'store'])->name('frontend.customer-inquiries.store');

Route::get('/gio-hang', [\App\Http\Controllers\Frontend\CartController::class, 'index'])->name('frontend.cart.index');
Route::post('/gio-hang/them', [\App\Http\Controllers\Frontend\CartController::class, 'add'])->name('frontend.cart.add');
Route::post('/gio-hang/xoa', [\App\Http\Controllers\Frontend\CartController::class, 'remove'])->name('frontend.cart.remove');

Route::get('/dat-tour/{id}/buoc-1', [\App\Http\Controllers\Frontend\CheckoutController::class, 'step1'])->name('frontend.checkout.step1');
Route::post('/dat-tour/{id}/buoc-1', [\App\Http\Controllers\Frontend\CheckoutController::class, 'processStep1'])->name('frontend.checkout.processStep1');

Route::middleware('auth')->group(function () {
    Route::get('/thanh-toan', [\App\Http\Controllers\Frontend\CheckoutController::class, 'index'])->name('frontend.checkout.index');
    Route::post('/thanh-toan', [\App\Http\Controllers\Frontend\CheckoutController::class, 'process'])->name('frontend.checkout.process');
    Route::get('/thanh-toan/thanh-cong', [\App\Http\Controllers\Frontend\CheckoutController::class, 'success'])->name('frontend.checkout.success');
});

Route::prefix('admin')->name('backend.')->group(function () {
    Route::name('admin.')->group(function () {
        Route::get('login', [AdminController::class, 'login'])->name('login');
        Route::post('login', [AdminController::class, 'authenticate'])->name('authenticate');
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');
    });

    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::name('admin.')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        });

        Route::resource('categories', CategoryController::class)
            ->except(['show'])
            ->names('categories');
        Route::get('categories/options', [CategoryController::class, 'options'])
            ->name('categories.options');
        Route::post('categories/quick-store', [CategoryController::class, 'quickStore'])
            ->name('categories.quick-store');
        Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
            ->name('categories.toggle-status');

        Route::resource('tour-options', TourOptionController::class)
            ->except(['show'])
            ->names('tour-options');
        Route::get('tour-options/options', [TourOptionController::class, 'options'])
            ->name('tour-options.options');
        Route::post('tour-options/quick-store', [TourOptionController::class, 'quickStore'])
            ->name('tour-options.quick-store');

        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ContentController::class, 'productIndex'])->name('index');
            Route::get('create', [ContentController::class, 'productCreate'])->name('create');
            Route::post('/', [ContentController::class, 'productStore'])->name('store');
            Route::get('{post}/edit', [ContentController::class, 'productEdit'])->name('edit');
            Route::put('{post}', [ContentController::class, 'productUpdate'])->name('update');
            Route::delete('{post}', [ContentController::class, 'productDestroy'])->name('destroy');
            Route::patch('{post}/toggle-status', [ContentController::class, 'productToggleStatus'])->name('toggle-status');
            Route::patch('{post}/toggle-featured', [ContentController::class, 'productToggleFeatured'])->name('toggle-featured');
        });

        Route::prefix('news')->name('news.')->group(function () {
            Route::get('/', [ContentController::class, 'newsIndex'])->name('index');
            Route::get('create', [ContentController::class, 'newsCreate'])->name('create');
            Route::post('/', [ContentController::class, 'newsStore'])->name('store');
            Route::get('{post}/edit', [ContentController::class, 'newsEdit'])->name('edit');
            Route::put('{post}', [ContentController::class, 'newsUpdate'])->name('update');
            Route::delete('{post}', [ContentController::class, 'newsDestroy'])->name('destroy');
            Route::patch('{post}/toggle-status', [ContentController::class, 'newsToggleStatus'])->name('toggle-status');
        });

        Route::prefix('administrative-units')->name('administrative-units.')->group(function () {
            Route::get('provinces', [AdministrativeUnitController::class, 'provinces'])->name('provinces');
            Route::get('wards', [AdministrativeUnitController::class, 'wards'])->name('wards');
        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'edit'])->name('edit');
            Route::put('/', [SettingController::class, 'update'])->name('update');
        });

        Route::prefix('seo')->name('seo.')->group(function () {
            Route::get('/', [SeoConfigController::class, 'edit'])->name('edit');
            Route::put('/', [SeoConfigController::class, 'update'])->name('update');
        });

        Route::resource('menus', MenuController::class)
            ->except(['show'])
            ->names('menus');
        Route::patch('menus/{menu}/toggle-status', [MenuController::class, 'toggleStatus'])
            ->name('menus.toggle-status');
        Route::patch('menus/{menu}/sort-order', [MenuController::class, 'updateSortOrder'])
            ->name('menus.update-sort-order');

        Route::resource('users', UserController::class)
            ->except(['show'])
            ->names('users');

        Route::resource('experts', ExpertController::class)
            ->except(['show'])
            ->names('experts');
        Route::patch('experts/{expert}/toggle-status', [ExpertController::class, 'toggleStatus'])
            ->name('experts.toggle-status');

        Route::resource('sliders', SliderController::class)
            ->except(['show'])
            ->names('sliders');
        Route::patch('sliders/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])
            ->name('sliders.toggle-status');

        Route::get('customer-inquiries', [BackendCustomerInquiryController::class, 'index'])
            ->name('customer-inquiries.index');

        Route::resource('orders', \App\Http\Controllers\Backend\OrderController::class)
            ->only(['index', 'show', 'update'])
            ->names('orders');

        Route::post('uploads/editor-image', [ContentController::class, 'uploadEditorImage'])
            ->name('admin.uploads.editor-image');
    });
});

Route::get('/{categorySlug}/{slug}', [PageController::class, 'contentShow'])->name('frontend.content.show');
Route::get('/{slug}', [PageController::class, 'categoryBySlug'])->name('frontend.categories.show');
