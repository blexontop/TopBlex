<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\AuthController;
use App\Http\Controllers\Site\AccountController;
use App\Http\Controllers\Site\OrderController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\FaqController;
use App\Mail\OrderConfirmedMail;
use App\Mail\WelcomeToTopblexMail;
use App\Mail\PasswordChangedMail;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/categories', [ProductController::class, 'categories'])->name('categories.index');

Route::get('/collections', [ProductController::class, 'collections'])->name('collections.index');

Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    Route::post('/login', [AuthController::class, 'attemptLogin'])->name('login.attempt');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');

    Route::put('/account', [AccountController::class, 'update'])->name('account.update');

    // Change password separately
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');

    Route::get('/checkout/stripe', [CheckoutController::class, 'show'])->name('stripe.checkout.show');
    Route::post('/checkout/stripe/session', [CheckoutController::class, 'createSession'])->name('stripe.checkout.session');
    Route::get('/checkout/stripe/success', [CheckoutController::class, 'success'])->name('stripe.checkout.success');
    Route::get('/checkout/stripe/success-page/{order}', [CheckoutController::class, 'successPage'])->name('stripe.checkout.success.page');
    Route::get('/checkout/stripe/cancel', [CheckoutController::class, 'cancel'])->name('stripe.checkout.cancel');

    Route::post('/orders/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'esadmin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::patch('/products/{product}/stock', [AdminProductController::class, 'updateStock'])
        ->name('products.stock');
    
    // Admin user management
    Route::resource('users', AdminUserController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
});

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

