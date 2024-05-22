<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\CustomersRepoetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoicesReports;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ProductController;
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
    return view('auth.login');
});


Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth', 'verified', 'CheckUser' ]);

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified', 'CheckUser' ])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::resource('invoices', InvoicesController::class);
Route::resource('sections', SectionController::class);
Route::resource('products', ProductController::class);

//Route::controller(InvoiceAttachmentController::class)->group(function(){
//    Route::post('attachment/store','store')->name('attachment.store');
//
//});

Route::resource('attachment', InvoiceAttachmentController::class);

Route::get('/section/{id}', [InvoicesController::class, 'getProducts']);

Route::get('invoicesDetails/{id}', [InvoiceDetailsController::class, 'edit'])->name('invoicesDetails');
Route::get('download/{invoice_number}/{file_name}', [InvoiceDetailsController::class, 'get_file']);
Route::get('view_file/{invoice_number}/{file_name}', [InvoiceDetailsController::class, 'open_file']);
Route::post('delete_file', [InvoiceDetailsController::class, 'destroy'])->name('delete_file');

Route::get('/status_show/{id}', [InvoicesController::class, 'show'])->name('status_show');

Route::post('update-status/{id}', [InvoicesController::class,'updateStatus'])->name('update_status');
Route::get('invoice_paid', [InvoicesController::class, 'invPaid'])->name('invoice.paid');
Route::get('invoice_unpaid', [InvoicesController::class, 'invUnPaid'])->name('invoice.unpaid');
Route::get('invoice_partial', [InvoicesController::class, 'invPartial'])->name('invoice.partial');
Route::get('print_invoice/{id}', [InvoicesController::class, 'printInvoice'])->name('print_invoice');

Route::resource('Archive', ArchiveController::class);

Route::get('export_invoices', [InvoicesController::class, 'export']);

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles',RoleController::class);
    Route::resource('users',UserController::class);
});

Route::controller(InvoicesReports::class)->group(function (){
    Route::get('invoices_reports', 'index');

});
Route::post('invoice_reports_all', [InvoicesReports::class, 'allReports']);
Route::post('search_invoice', [InvoicesReports::class, 'search_invoice']);

Route::get('customers_report', [CustomersRepoetController::class, 'index']);
Route::post('customers_search', [CustomersRepoetController::class, 'customersSearch'])->name('customersSearch');
Route::get('/notifications/read_all', [InvoicesController::class, 'markAllAsRead'])->name('All.Read');




Route::resource('{page}', \App\Http\Controllers\AdminController::Class);//هاي الroute لازم تضل اخر شي
//كان في بالباراميتير كلمة  page
//منحط كلمة page مشان نغضر نجيب كل الصفحات مشان ما يطلعلنا page not found
