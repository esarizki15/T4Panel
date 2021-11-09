<?php

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


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/landing', 'StaticPageController@index')->name('iglow.landing');
Route::get('/privacy-policy', 'StaticPageController@privacyPolicy')->name('iglow.pp');

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {

	Route::group([ 'prefix' => 'user'], function () {

	    Route::resource('access-users', 'UserBundle\AccessUserController');
	    Route::resource('menu', 'UserBundle\MenuController');
	    Route::resource('sub-menu', 'UserBundle\SubMenuController');
	    Route::resource('navi', 'UserBundle\NavigationController');
	    Route::resource('profile', 'UserBundle\ProfileController');

	});

	Route::group([ 'prefix' => 'member-manage'], function () {
		Route::resource('member', 'MemberBundle\MemberController');
	});

	Route::group([ 'prefix' => 'content-manage'], function () {
		Route::resource('news', 'ContentBundle\NewsController');
		Route::resource('news-category', 'ContentBundle\NewsCategoryController');
	});
 
	Route::group([ 'prefix' => 'banner-manage'], function () {
		Route::resource('banner-market', 'BannerBundle\BannerMarketController');
		Route::resource('banner-home', 'BannerBundle\BannerHomeController');
		Route::resource('banner-news', 'BannerBundle\BannerNewsController');
	});
 
	Route::group([ 'prefix' => 'master-data'], function () {
		Route::resource('category', 'MasterBundle\CategoryController');
		Route::resource('category-product', 'MasterBundle\CategoryProductController');
		Route::resource('log-sms', 'MasterBundle\LogSmsController');
		Route::resource('page-static', 'MasterBundle\PagesController');
		Route::resource('metode-pembayaran', 'MasterBundle\MetodePembayaranController');
		Route::resource('setting', 'MasterBundle\SettingController');
		Route::resource('acquisition-token-otp', 'MasterBundle\AcquisitionTokenOtpController');
		Route::get('generate-acquisition-token-otp', 'MasterBundle\AcquisitionTokenOtpController@generateTokenAcquisition')->name('acquisition-token-otp.generate');
		
	});

	Route::group([ 'prefix' => 'product-manage'], function () {
		Route::resource('order', 'ProductBundle\OrderController');
		Route::resource('order-detail', 'ProductBundle\OrderDetailController');
	});

	Route::group([ 'prefix' => 'voucher-manage'], function () {
		Route::resource('voucher', 'VoucherBundle\VoucherController');
		Route::resource('voucher-category', 'VoucherBundle\VoucherCategoryController');
		Route::resource('voucher-partner', 'VoucherBundle\VoucherPartnerController');
		Route::resource('voucher-usage', 'VoucherBundle\VoucherUsageController');
		Route::resource('point-history', 'VoucherBundle\PointHistoryController');
		Route::resource('point-scenario', 'VoucherBundle\PointScenarioController');
	});
	Route::group([ 'prefix' => 'marketing-manage'], function () {
		Route::resource('push-notification', 'MarketingBundle\PushNotificationController');
		Route::get('create-batch', 'MarketingBundle\PushNotificationController@createBatch')->name('push-notification.create-batch');
		Route::post('store-batch', 'MarketingBundle\PushNotificationController@storeBatch')->name('push-notification.store-batch');
	});
	Route::group([ 'prefix' => 'appointment-manage'], function () { 
		Route::resource('appointment-status', 'AppointmentBundle\AppointmentStatusController'); 
	});
		 
	Route::group([ 'prefix' => 'residentservice-manage'], function () {
		Route::resource('residentservice-member', 'ResidentServiceBundle\ResidentServiceMemberController');
		Route::resource('residentservice-access-user', 'ResidentServiceBundle\ResidentServiceAccessUserController');
		Route::resource('residentservice-report', 'ResidentServiceBundle\ResidentServiceReportController');
		Route::resource('residentservice-schedule', 'ResidentServiceBundle\ResidentServiceScheduleController');
		Route::resource('residentservice-service-location', 'ResidentServiceBundle\ResidentServiceServiceLocationController');
		Route::resource('residentservice-services', 'ResidentServiceBundle\ResidentServiceServicesController');
		Route::resource('residentservice-sub-services', 'ResidentServiceBundle\ResidentServiceSubServicesController');
		Route::resource('residentservice-event-qr-pic', 'ResidentServiceBundle\EventQrPicController');
		Route::resource('residentservice-calendar', 'ResidentServiceBundle\ResidentServiceCalendarController');
		Route::resource('residentservice-scanner-activity', 'ResidentServiceBundle\ResidentServiceScannerActivityController');
		Route::put('residentservice-member-update-start/{id}/', 'ResidentServiceBundle\ResidentServiceMemberController@updateStart')->name('residentservice-member.update-start');
		Route::put('residentservice-member-stop/{id}', 'ResidentServiceBundle\ResidentServiceMemberController@updateStop')->name('residentservice-member.update-stop');
		Route::put('residentservice-member-cancel/{id}', 'ResidentServiceBundle\ResidentServiceMemberController@updateCancel')->name('residentservice-member.update-cancel');
		Route::put('residentservice-member-update-notes/{id}', 'ResidentServiceBundle\ResidentServiceMemberController@updateNotes')->name('residentservice-member.update-notes');
		Route::get('residentservice-summary-data', 'ResidentServiceBundle\ResidentServiceMemberController@summaryData')->name('residentservice-member.summary-data');
		Route::put('residentservice-calendar-update-start/{id}/', 'ResidentServiceBundle\ResidentServiceCalendarController@updateStart')->name('residentservice-calendar.update-start');
		Route::put('residentservice-calendar-stop/{id}', 'ResidentServiceBundle\ResidentServiceCalendarController@updateStop')->name('residentservice-calendar.update-stop');
		Route::put('residentservice-calendar-cancel/{id}', 'ResidentServiceBundle\ResidentServiceCalendarController@updateCancel')->name('residentservice-calendar.update-cancel');
		Route::put('residentservice-calendar-update-notes/{id}', 'ResidentServiceBundle\ResidentServiceCalendarController@updateNotes')->name('residentservice-calendar.update-notes');
	});
	Route::group([ 'prefix' => 'visitor-manage'], function () {
		Route::resource('visitor-member', 'VisitorBundle\MemberController');
		Route::resource('visitor-access-user', 'VisitorBundle\AccessUserController');
		Route::resource('visitor-report', 'VisitorBundle\ReportController');
		Route::resource('visitor-schedule', 'VisitorBundle\ScheduleController');
		Route::resource('visitor-location', 'VisitorBundle\ServiceLocationController');
		Route::resource('visitor-services', 'VisitorBundle\ServicesController');
		Route::resource('visitor-sub-services', 'VisitorBundle\SubServicesController');
		Route::resource('visitor-event-qr-pic', 'VisitorBundle\EventQrPicController');
		Route::resource('visitor-calendar', 'VisitorBundle\CalendarController');
		Route::put('visitor-member-update-start/{id}/', 'VisitorBundle\MemberController@updateStart')->name('visitor-member.update-start');
		Route::put('visitor-member-stop/{id}', 'VisitorBundle\MemberController@updateStop')->name('visitor-member.update-stop');
		Route::put('visitor-member-cancel/{id}', 'VisitorBundle\MemberController@updateCancel')->name('visitor-member.update-cancel');
		Route::put('visitor-member-update-notes/{id}', 'VisitorBundle\MemberController@updateNotes')->name('visitor-member.update-notes');
		Route::get('visitor-summary-data', 'VisitorBundle\MemberController@summaryData')->name('visitor-member.summary-data');
		Route::put('visitor-calendar-update-start/{id}/', 'VisitorBundle\CalendarController@updateStart')->name('visitor-calendar.update-start');
		Route::put('visitor-calendar-stop/{id}', 'VisitorBundle\CalendarController@updateStop')->name('visitor-calendar.update-stop');
		Route::put('visitor-calendar-cancel/{id}', 'VisitorBundle\CalendarController@updateCancel')->name('visitor-calendar.update-cancel');
		Route::put('visitor-calendar-update-notes/{id}', 'VisitorBundle\CalendarController@updateNotes')->name('visitor-calendar.update-notes');
	}); 
});




Route::group(['middleware' => 'auth', 'prefix' => 'api'], function () {
 	

	Route::group([ 'prefix' => 'users'], function () {

	    Route::post('menu/add', 'UserBundle\AccessUserRoleController@add'); 
	    Route::post('menu/remove', 'UserBundle\AccessUserRoleController@remove'); 

	});
 

});