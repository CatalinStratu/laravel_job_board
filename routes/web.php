<?php
Route::get('/', 'IndexController@index')->name('home.index');
Auth::routes(['verify' => true]);

Route::get('signaturepad','SignaturePadController@index');
Route::post('signaturepad','SignaturePadController@upload')->name('signaturepad.upload');

Route::get('/about', 'IndexController@about')->name('home.about');
Route::get('/pricing', 'IndexController@pricing')->name('home.pricing');
Route::get('/how-it-works', 'IndexController@howitworks')->name('how-it-works');
Route::get('/terms', 'IndexController@terms')->name('terms');
Route::get('/copyright', 'IndexController@copyright')->name('copyright');
Route::get('/jobs', 'SearchController@index')->name('home.search');
Route::get('/remote-jobs', 'IndexController@remote')->name('home.remote');
Route::get('/jobs-that-pay-in-cryptocurrency', 'IndexController@crypto')->name('home.paid_in_crypto');
Route::get('/category/{slug}', 'IndexController@category')->name('home.category');
Route::get('/jobs/{slug}', 'JobsController@show')->name('home.job');
Route::post('/uploade/img', 'HomeController@store')->name('home.uploade');
Route::prefix('dashboard')->group(function () {
    Route::middleware(['auth'])->group(function () {
        
        //Dashboard
        Route::get('/', 'DashboardController@index')->name('dashboard.index');
        Route::get('/change-password', 'SettingController@changePass')->name('dashboard.changePass');
        Route::post('/change-password', 'SettingController@changePassPost');
        //Text editor
        Route::post('ckeditor/upload', 'CkeditorController@upload')->name('ckeditor.upload');
            /**
            * ------------------------------------------------------------------------
            * Company Dashboard Routes
            * ------------------------------------------------------------------------
            * 
            */
            Route::middleware('auth.company')->group(function () {
                Route::get('/company/add', 'CompaniesController@index')->name('company.add.get');
                Route::post('/company/add', 'CompaniesController@add')->name('company.add.post');
            });
            Route::middleware('company')->group(function () {
                    //Route::get('/transactions', 'BalanceController@transactions')->name('balance.add.transactions');
                    //checkout
                    //Route::get('/checkout/{package_id}', 'PaymentController@checkout')->name('checkout');
                    //Route::post('/checkout/{package_id}', 'PaymentController@checkoutPost');
                    // Add jobs
                Route::get('/add-job', 'JobsController@index')->name('job.add.get');
                Route::get('/edit-job/{slug}', 'JobsController@edit')->name('job.edit.get');
                Route::post('/edit-job/{slug}', 'JobsController@edit_job')->name('job.edit.post');
                Route::get('/delete-job/{slug}', 'JobsController@delete')->name('job.delete.get');
                Route::post('/add-job', 'JobsController@newJobPost')->name('job.add.post');
                Route::get('/posted/jobs', 'JobsController@postedjobs')->name('job.posted.get');
                Route::get('/add-member', 'TeamController@add')->name('team.add.get');
                Route::post('/add-member', 'TeamController@addmember')->name('team.add.post');
                Route::get('/company/update', 'CompaniesController@edit')->name('company.update.get');
                Route::post('/company/update', 'CompaniesController@update')->name('company.update.post'); 
            });
        //});        
    });
});

Auth::routes();
