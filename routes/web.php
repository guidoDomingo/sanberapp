<?php

use App\Http\Controllers\BeneficiarioController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Beneficiarios Routes
Route::get('/', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
Route::match(['get', 'post'], '/beneficiarios/search', [BeneficiarioController::class, 'search'])->name('beneficiarios.search');
Route::post('/beneficiarios/import', [BeneficiarioController::class, 'importExcel'])->name('beneficiarios.import');
