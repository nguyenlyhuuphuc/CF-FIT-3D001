<?php

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Service\VNPaySerivce;
use App\Jobs\TestJob;
use App\Mail\OrderClientEmail;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Database\Factories\ProductFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

use function Laravel\Prompts\error;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function (){
    return view('client.layout.master');
})->name('home');

Route::get('home', [HomeController::class, 'index'])->name('home');

Route::get('shop-grid', function(){
    return view('client.pages.shop-grid');
});

Route::get('shop-detail',function(){
    return view('client.pages.shop-detail');
});

Route::get('cart/add-item/{id}/{qty?}', [CartController::class, 'add'])
->name('cart.add.item')->middleware('auth');
Route::get('cart/delete-item/{id}', [CartController::class, 'delete'])
->name('cart.delete.item')->middleware('auth');
Route::get('cart/remove-cart', [CartController::class, 'remove'])
->name('cart.remove.cart')->middleware('auth');
Route::get('cart/update-item/{id}/{qty?}', [CartController::class, 'update'])
->name('cart.update.item')->middleware('auth');

Route::get('cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth');
Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout')->middleware('auth');
Route::post('place-order', [CartController::class, 'placeOrder'])->name('cart.place.order')->middleware('auth');

Route::get('shop-detail/{slug}', [ProductController::class, 'getBySlug'])->name('product.get.by.slug');

Route::get('call-back-vnpay', [VNPaySerivce::class, 'callBackVNPay'])->name('call.back.vnpay');

Route::get('redirect-google', [GoogleController::class, 'redirect'])->name('google.redirect');
 
Route::get('call-back-google', [GoogleController::class, 'callback'])->name('google.call.back');

Route::get('test-sms', function(){
    $receiverNumber = '+84352405575';
    $message = 'Xac nhan don hang thanh cong http://localhost:8000/order/detail/26';
    $client = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
    $client->messages->create(
        $receiverNumber, 
        [
            'from' => env('TWILIO_PHONE_NUMBER'),
            'body' => $message
        ]
    );
});

Route::get('test-url', function (){
    $builder = new \AshAllenDesign\ShortURL\Classes\Builder();

    $shortURLObject = $builder->destinationUrl('http://localhost:8000/order/detail/26')->make();
    $shortURL = $shortURLObject->default_short_url;
});

Route::get('test-delete', function(){
    $productCategory = ProductCategory::find(10);
    $productCategory->forceDelete();
});

Route::get('test-insert', function(){
    ProductCategory::create([
        'name' => 'aaaaaaaaa',
        'slug' => 'aaaaaaaaa'
    ]);
});

Route::get('test-log', function(){
    Log::info('Day la log cua tui');
    Log::error('Day la error');
    Log::critical('Day la critical');
    Log::notice('notice');
});

Route::get('test-job', function(){
    echo '<h1>'. Carbon::now()->format('d-m-Y H:i:s').'</h1>';
    TestJob::dispatch();
});