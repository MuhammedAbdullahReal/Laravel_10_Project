<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\Profile\AIAvatarController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});


Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::patch('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
  Route::patch('/profile/AIAvatar', [AIAvatarController::class, 'AddAIAvatar'])->name('profile.AIAvatar');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/auth/redirect', function () {
  return Socialite::driver('github')->redirect();
})->name('login.github');

Route::get('/auth/callback', function () {
  $githubUser = Socialite::driver('github')->user();


    $user = User::firstOrCreate([
      'email' => $githubUser->email,
    ], [
      'name' => $githubUser->name,
      'password' => bcrypt(Str::random(16)),
       
    ]);

    Auth::login($user);

    return redirect('/dashboard');
  });



require __DIR__ . '/auth.php';
