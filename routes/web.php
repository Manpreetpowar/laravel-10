<?php

use App\Http\Controllers;
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

Auth::routes();

Route::get('/', function () {
    return redirect('dashboard');
});

/**
 *
 * Superadmin Routes
 */
// Route::group(['middleware' => 'auth'], function() {

    /**
     * ---------------------------------------
     *  Common Routes
     * ---------------------------------------
     */
    // Route::get('/', );
    Route::get('/dashboard', 'HomeController@index')->name('home');

    // file upload
    Route::post('fileUpload', 'AttachmentController@save');

    /**
     * ---------------------------------------
     *  Admin and Account routes
     * ---------------------------------------
     */
    Route::group(['middleware' => 'role:administrator'], function() {

        Route::get('users/filter', 'UserController@filter');
        Route::resource('users', 'UserController');

        //machine routes
        Route::get('machine/{id}/jobs-list','MachineController@jobsList');
        Route::get('machine/ad-hoc-services','AdHocServiceController@list');
        Route::get('machine-jobs/{id}/filter', 'MachineController@machineFilter');
        Route::resource('machines', 'MachineController');

        //ad hoc service routes
        Route::resource('ad-hoc-services', 'AdHocServiceController');
        Route::post('ad-hoc-service/update-remark/{service_id}', 'AdHocServiceController@updateRemark');
        //file upload Service routes
        Route::get('ad-hoc-services/upload-file/{service_id}', 'AdHocServiceController@uploadFileModal');
        Route::post('ad-hoc-services/upload-file/{service_id}', 'AdHocServiceController@uploadFile');
        Route::get('ad-hoc-services/update-date/{service_id}', 'AdHocServiceController@updateDateModal');
        Route::post('ad-hoc-services/update-date/{service_id}', 'AdHocServiceController@updateDate');

    });

    /**
     * ---------------------------------------
     *  Admin and Floor Manager routes
     * ---------------------------------------
     */
    Route::group(['middleware' => 'role:floor_manager,administrator'], function() {

        // inventory routes
        Route::resource('inventories', 'ProductController');
        Route::post('inventories/color-match/status-change/{id}', 'ProductController@changeColorMatchStatus');

        // floor operations routes
        Route::group(['prefix' => 'floor-operations'], function(){
            Route::get('/', 'FloorOperationController@index')->name('floor-operations.index');
            Route::get('orders', 'FloorOperationController@managerModule');
            Route::get('completed-jobs/list', 'FloorOperationController@completedOrders');
            Route::get('pending-orders/list', 'FloorOperationController@pendingOrders');
            Route::get('all-jobs', 'FloorOperationController@allJobsModule');
            Route::get('all-jobs/list', 'FloorOperationController@allJobsList');
            Route::get('all-jobs/filter', 'FloorOperationController@allJobsFilter');

            //jobs on hld
            Route::get('jobs-on-hold', 'FloorOperationController@jobsOnHold');
            Route::controller(JobsOnHoldController::class)->group(function () {
                Route::get('service-orders/no-credit-jobs', 'noCreditJobs');
                Route::get('service-orders/manual-print-require-jobs', 'manualPrintRequireJobs');
                Route::get('on-hold/service-orders/{id}', 'showOnHoldOrders');
                Route::get('on-hold/service-orders/generate-invoice/{id}', 'generateInvoice');
                Route::get('on-hold/service-orders/mark-delivery/{id}', 'markForDelivery');
                Route::get('manual-print/mark-all-for-delivery', 'markAllDelivery');

                //invoice
                Route::get('manual-print/print-all-invoices', 'printAllInvoices');
                Route::get('print-invoice/{id}', 'printInvoice');

            });

            //get color
            Route::get('color-match-module', 'ColorMatchController@index');
            Route::post('get-color', 'ColorMatchController@getColor');
        });

        // service orders routes
        Route::get('service-orders/add-item/{type}', 'ServiceOrderController@addItem');
        Route::put('service-orders/confirm-received/{id}', 'ServiceOrderController@confirmReceived');


    });

    /**
     * ---------------------------------------
     *  Admin, Floor Manager and Operator routes
     * ---------------------------------------
     */
    Route::group(['middleware' => 'role:operator,administrator,floor_manager', 'prefix' => 'floor-operations'], function() {
        //machining module
        Route::get('machines', 'FloorOperationController@machineModule');
        Route::controller(MachineController::class)->group(function () {
            Route::get('operate-machines/{id}', 'becomeMachineOperator');
            Route::get('machines/{id}/operate', 'operateMachine');
            Route::get('machines/{id}/leave', 'leaveMachine');
            // Route::get('pending-machines/list', 'pendingMachines');
            Route::get('{machine_id}/received-orders/list', 'receivedOrders');
            Route::get('operate-machine/{machine_id}/service-orders/{order_id}', 'operateMachineOrder');
            Route::put('operate-machine/{machine_id}/service-orders/{order_id}', 'saveMachineMileage');
        });

        //qc
        Route::get('pending-qc', 'FloorOperationController@qcModule');
        Route::controller(QcController::class)->group(function () {
            Route::get('pending-qc/list', 'pendingQc');
            Route::get('service-orders/qc-check/{id}', 'qcCheck');
            Route::get('service-orders/{id}/qc-pass/', 'qcPass');
        });
    });

    /**
     * ---------------------------------------
     *  Admin and Account routes
     * ---------------------------------------
     */
    Route::group(['middleware' => 'role:account,administrator'], function() {
        // Client routes
        Route::group(['prefix' => 'clients'], function(){
            Route::get('credit-notes', 'CreditNoteController@list');
            Route::get('{id}/jobs-list', 'ClientController@jobsList');
            Route::get('{id}/download-all-unpaid-invoices', 'ClientController@downloadAllUnpaidInvoices');
        });

        Route::get('clients/service-orders/{id}', 'ClientController@viewJob');
        Route::get('clients/service-order/filter', 'ClientController@jobListfilter');
        Route::resource('clients', 'ClientController');

        // Account Statement routes accounts-statements
        Route::group(['prefix' => 'account-statement'], function(){
            Route::get('filter', 'AccountStatementsController@accountStatementfilter');
            Route::get('{id}/statement-list', 'AccountStatementsController@statementsList');
            Route::post('status-change/{id}','AccountStatementsController@changeStatus')->name('status-change');
            Route::post('generate-statement', 'AccountStatementsController@generateAccountStatement');
            Route::get('generate-statement/{client_id}', 'AccountStatementsController@accountStatementView');
            Route::get('destroy-soa/{id}', 'AccountStatementsController@destroy');
            Route::get('download-soa-invoice/{id}', 'AccountStatementsController@downloadStatementofAccountInvoice');
        });

        // Credit notes and items
        Route::get('credit-notes/filter', 'CreditNoteController@filter');
        Route::resource('credit-notes', 'CreditNoteController');
        Route::get('credit-note/add-item', 'CreditNoteController@addItem');
        Route::post('credit-notes/update-remark/{id}', 'CreditNoteController@updateRemark');
        Route::delete('credit-items/destroy/{id}', 'CreditNoteController@delete_item');
        Route::get('credit-notes/download-invoice/{id}', 'CreditNoteController@downloadCreditNotesInvoice');
        Route::post('credit-notes/status-change/{id}','CreditNoteController@creditNoteStatusChange');

        // accountings routes
        Route::group(['prefix' => 'accountings'], function(){
            Route::get('invoices', 'AccountingController@invoiceModule');
            Route::get('invoices/list', 'AccountingController@invoiceList');
            Route::post('invoices/status-change/{id}','AccountingController@changeInvoiceStatus');

            Route::get('expenses', 'ExpenseController@index');
            Route::get('expenses/list', 'ExpenseController@list');
            Route::get('expenses/create', 'ExpenseController@create');
            Route::post('expenses/store', 'ExpenseController@store');
            Route::get('expenses/{id}/edit', 'ExpenseController@edit');
            Route::put('expenses/update/{id}', 'ExpenseController@update');
            Route::delete('expenses/delete/{id}', 'ExpenseController@destroy');
            Route::post('expenses/update-remark/{id}', 'ExpenseController@updateRemark');
            Route::get('expenses/filter', 'ExpenseController@expensefilter');
        });

        //invoices
        Route::resource('accountings', 'AccountingController');
        Route::get('invoice/filter', 'AccountingController@invoiceFilter');

        //Setting route
        Route::put('settings/update', 'SettingController@updateSetting')->name('setting.update');
        Route::resource('settings', 'SettingController');

    });

    /**
     * ---------------------------------------
     *  Admin, Floor Manager and Driver routes
     * ---------------------------------------
     */
    Route::group(['middleware' => 'role:floor_manager,driver,administrator'], function() {

        Route::controller(DriverController::class)->group(function () {
            Route::get('driver/service-orders', 'driverOrders');
            Route::get('driver/service-orders/pending-jobs', 'driverPendingOrdersList');
            Route::get('driver/service-orders/completed-jobs', 'driverCompletedOrdersList');
            Route::get('driver/service-orders/pending-delivery', 'pendingDelivery');
            Route::get('driver/service-orders/pending-delivery/list', 'pendingDeliveryList');
            Route::get('driver/service-orders/{id}', 'showDriverOrders');
            Route::get('service-orders/pending-delivery/{id}', 'pendingDeliveryItem');
            Route::post('service-orders/pending-delivery/{id}/mark-delivered', 'pendingDeliveryMarkDeliver');
        });
        Route::get('floor-operations/download-invoice/{id}','JobsOnHoldController@downloadInvoice');
           //driver module
        Route::get('floor-operations/driver', 'FloorOperationController@driverModule');
        Route::resource('service-orders', 'ServiceOrderController');
    });


// });
