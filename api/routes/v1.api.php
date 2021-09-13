<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

// Unprotected routes
Route::middleware("cors")->group(function() {

    Route::get("/", function () {
        return "Valucart 2 API's";
    });

    Route::options("{any?}", function () {
        return new Response();
    })->where("any", ".*");
    
    Route::get("termsofuse", "StaticsController@termsofuse");
    Route::get("terms-and-conditions", "StaticsController@termsofuse");
    Route::get("returnpolicy", "StaticsController@returnpolicy");
    Route::get("return-policy", "StaticsController@returnpolicy");
    Route::get("privacypolicy", "StaticsController@privacypolicy");
    Route::get("privacy-policy", "StaticsController@privacypolicy");
    Route::get("about-us", "StaticsController@aboutus");
    Route::get("faq", "StaticsController@faqs");
    Route::get("versions", "SystemsettingsController@show");

    Route::get("states", "GetStatesController@index");
    Route::get("states/{state_id}", "GetStatesController@show");
    Route::post("states", "CreateStateController@store");
    
    Route::post("customers", "CustomersSignupController@store")->name("customer_signup");
    
    Route::get("brands", "BrandsController@index")->name("brands_list");
    Route::get("brands/{brand_id}", "BrandsController@show")->name("brand_details");
    Route::post("brands", "BrandsController@store")->name("create_brands");
    
    Route::get("availableoffers", "OffersController@index")->name("availableoffers_list");
    Route::get("availableoffers/{offer_id}", "OffersController@show")->name("availableoffers_details");
    Route::post("update_bankoffer_image/{offer_id}", "OffersController@update_bankoffer_image");


    Route::get("popularcustomers", "PopularcustomersController@index")->name("popularcustomers_list");
    Route::get("popularcustomers/{customer_id}", "PopularcustomersController@show")->name("popularcustomers_details");

    Route::post("adminlogin", "AdminController@login")->name("login");

    Route::post("location", "AdminController@location")->name("login");

    Route::post("triggerpush", "AdminController@triggerpush")->name("triggerpush");
    Route::post("notificationimage", "AdminController@uploadnotificationimage");
    Route::get("generatehash/{id}", "AdminController@generatehash")->name("hash");

    Route::get("countries", "CountriesController@index")->name("list_countries");
    Route::get("countries/{country_id}", "CountriesController@show")->name("country_details");

    Route::get("countries", "CountriesController@index")->name("list_countries");
    Route::get("countries/{country_id}", "CountriesController@show")->name("country_details");
    
    Route::get("categories", "CategoriesController@index")->name("categories_list");
    Route::get("categories/{category_id}", "CategoriesController@show")->name("category_details");
    Route::get("categories_by_location/", "CategoriesController@getNearByCategories")->name('categories_by_location');
    Route::post("categories", "CategoriesController@store")->name("create_categories");

    Route::get("department_categories", "DepartmentCategoryController@index")->name("department_categories_list");
    Route::get("department_categories/{category_id}", "DepartmentCategoryController@show")->name("department_category_details");
    Route::post("department_categories", "DepartmentCategoryController@store")->name("create_department_categories");
    
    Route::get("subcategories", "SubcategoriesController@index")->name("subcategories_list");
    Route::get("subcategories/{subcategory_id}", "SubcategoriesController@show")->name("subcategory_details");
    Route::post("subcategories", "SubcategoriesController@store")->name("create_subcategories");
    
    Route::get("vendors", "VendorsController@index")->name("vendors_list");
    Route::get("vendors/{vendor_id}", "VendorsController@show")->name("vendors_details");
    Route::post("vendors", "VendorsController@store")->name("create_vendors");
    Route::post("vendors/updateimage", "VendorsController@update")->name("update_vendor_image");
    
    Route::get("metric_units", "MetricUnitsController@index");
    Route::get("metric_units/{unit_id}", "MetricUnitsController@show");
    Route::post("metric_units", "MetricUnitsController@store");
    
    Route::get("products", "ProductsController@index")->name("products_list");
    Route::get("products/{product}", "ProductsController@show")->name("products_details");
    Route::post("products", "CreateProductController@store")->name("create_product");
    Route::post("products/update", "ProductsController@edit")->name("edit_product");
    
    Route::get("productbysku", "ProductsController@get_by_sku");
    Route::get("products/update/images", "ProductsUpdateImages@run");
    Route::post("products/upload", "ProductsUploadController@upload");
    Route::post("products/bulkupdate", "ProductsUploadController@bulkupdate");
    Route::post("products/bulk_priceupdate", "ProductsUploadController@bulk_priceupdate");
    Route::post("products/bulkupload", "ProductsUploadController@bulkupload");
    Route::post("products/meatmondayupload", "ProductsUploadController@meatmondayupload");

    Route::post("update_product_image/{product_id}", "BundlesController@update_bundle_image");

    Route::post("update_products_image/{product_id}", "ProductsController@update_products_image");

    Route::post("update_products_thumpimage/{product_id}", "ProductsController@update_products_thumpimage");

    Route::post("delete_products_image", "ProductsController@delete_products_image");

    Route::get("communities", "CommunitiesController@index")->name("communities_list");
    Route::get("communities/{community_id}", "CommunitiesController@show")->name("community_details");
    Route::post("communities", "CommunitiesController@store")->name("create_community");
    
    Route::post("bundlecategories", "BundlesCategoriesController@store")->name("create_bundlecategories");
    Route::get("bundlecategories", "BundlesCategoriesController@index")->name("bundlecategorieslist");
    Route::get("bundlecategories/{bundlecategory}", "BundlesCategoriesController@show")->name("bundlecategories_details");
    
    Route::get("img/{folder}/{path}", "ImagesController@handle")->where("path", ".*");
    
    // Route::get("/bundles", "BundlesController@index")->name("bundle_details");
    // Route::get("/bundles/{bundle_id}", "BundlesController@show")->name("bundle_details");
    // Route::get("/bundles_alternative_products", "BundlesController@show_alternatives")->name("alternative_products");
    
    // Route::post("/bundles", "BundlesController@store")->name("create_bundle");
    // Route::post("/bundles/products", "BundlesController@store_products")->name("create_bundle_products");
    // Route::post("/bundles/products/alternatives", "BundlesController@store_alternative_products")->name("create_bundle_products_alternatives");
    // Route::post("/bundles/updatebundleimage", "BundlesController@update")->name("update_bundle");
    // Route::post("/bundles/updatebundle", "BundlesController@edit")->name("update_bundle_details");
    // Route::post("/bundles/popular", "BundlesController@update_popular")->name("update_popular_bundle_details");
    // Route::post("/bundles/updatebundle_quantity", "BundlesController@edit_quantity")->name("update_bundle_quantity");
    
    Route::post("departments", "DepartmentsConroller@store")->name("create_departments");
    Route::get("departments", "DepartmentsConroller@index")->name("departments_list");
    Route::get("departments/{department}", "DepartmentsConroller@show")->name("departments_details");
    // Route::get("departments/near_by/{category_id}", "DepartmentsConroller@getDepartmentsByCordinates")->name("get_departments_by_cordinates");
    
    Route::get("departments/near_by/{category_id}", "DepartmentsConroller@getVendorsByCordinates")->name("get_departments_by_cordinates");
        
    Route::post("update_department_icon/{department_id}", "DepartmentsConroller@update_department_icon");
    Route::post("update_department_image/{department_id}", "DepartmentsConroller@update_department_image");


    Route::get("search", "SearchController@handle");
    
    Route::post("customerbundles", "CustomerBundleController@edit")->name("create_customerbundle");
    Route::get("customerbundles/{bundle}", "CustomerBundleController@show")->name("customerbundle_details");
    Route::put("customerbundles/{bundle}", "CustomerBundleController@update")->name("update_customerbundlename");

    Route::post("delete_customerbundle", "CustomerBundleController@delete")->name("delete_customerbundle");
    // Route::post("/customerbundles/bundle", "CustomerBundleController@edit")->name("bundle_action");

    Route::get("feedback", "FeedbackController@index");
    Route::get("feedback/{feedback_id}", "FeedbackController@show");
    Route::post("trigger_push", "FeedbackController@sendpush");

    Route::post("customers/account_recovery", "AccountRecoveryController@get_customer_identifier");
    Route::post("customers/account_recovery/recover", "AccountRecoveryController@recovery_with_code");

    Route::get("broadcast/types", "BroadcastController@types");
    Route::get("broadcast", "BroadcastController@index");
    Route::get("broadcast/{message_id}", "BroadcastController@show");
    Route::post("broadcast", "BroadcastController@store");
    Route::post("broadcast/{message_id}", "BroadcastController@update");
    Route::delete("broadcast/{message_id}", "BroadcastController@delete");

});

Route::get("img/{folder}/{path}", "ImagesController@handle")->where("path", ".*");

// This middleware uses the user information if it"s availble
Route::middleware("cors", "auth.check_customer:customers")->group(function() {

    Route::get("areas", "AreasController@index");
    Route::get("areas/{area_id}", "AreasController@show");

    
    Route::get("banners/positions", "BannersController@get_banner_positions");
    Route::get("banners/resource_types", "BannersController@get_resource_types");

    Route::post("banners", "BannersController@store");
    Route::get("banners/{position_name?}", "BannersController@index");
    Route::get("banners/detail/{banner_id}", "BannersController@show");
    Route::post("banners/update/{banner_id}", "BannersController@update");
    Route::post("banners/publishUnpublish/{banner_id}", "BannersController@publishUnpublish");
    Route::post("banners/reorder", "BannersController@reorder");
    Route::delete("banners/{banner_id}", "BannersController@destroy");

    Route::get("bundles/categories", "BundlesCategoriesController@index");
    Route::get("bundles/categories/{category_id}", "BundlesCategoriesController@show");
    Route::post("bundles/categories", "BundlesCategoriesController@store");
    Route::put("bundles/categories/{category_id}", "BundlesCategoriesController@update");

    Route::post("update_bundle_image/{bundle_id}", "BundlesController@update_bundle_image");
    Route::get("show_single_bundle/{bundle_id}", "BundlesController@show_single_bundle");
    Route::get("show_single_order/{order_id}", "OrdersController@show_single_order");
    Route::get("show_all_orders", "OrdersController@show_all_orders");
    Route::post("orderstatuschange", "OrdersController@statuschange")->name("order_status_change");
    Route::post("item_availability", "OrdersController@item_availability")->name("item_availability");
    Route::post("alterorder", "AdminController@alterorder")->name("alter_order");


    Route::get("bundles", "BundlesController@index");
    Route::get("bundles/{bundle_id}", "BundlesController@show");
    Route::post("bundles", "BundlesController@store");
    Route::post("bundles/{bundle_id}", "BundlesController@update");
    Route::delete("bundles/{bundle_id}", "BundlesController@destroy");
    Route::post("bundles/{bundle_id}/products", "BundleProductsController@add_products");
    Route::delete("bundles/{bundle_id}/products/{product_id}", "BundleProductsController@remove_product");
    Route::get("bundles/{bundle_id}/products/{product_id}/alt", "BundleProductsController@add_alt_products");
    Route::post("bundles/{bundle_id}/products/{product_id}/alt", "BundleProductsController@add_alt_products");
    Route::delete("bundles/{bundle_id}/products/{product_id}/alt/{alt_product_id}", "BundleProductsController@remove_alt_products");

    Route::post("checkout/payment", "PaymentController@request_payment");
    Route::get("checkout/payment/poll/{order_id}", "PaymentController@poll_payment");
    Route::get("checkout/payment/verify/{order_id}", "PaymentController@verify_payment");

    Route::post("cart", "CartController@store");
    Route::get("cart/{cart?}", "CartController@show");
    Route::post("cart/coupon", "CouponController@apply_coupon");

    Route::get("deliverytime", "DeliveryTimesController@index");
    Route::get("deliverytime/{id}", "DeliveryTimesController@show");
    Route::post("deliverytime", "DeliveryTimesController@store");
    Route::post("deliverytime/{id}", "DeliveryTimesController@update");
    Route::delete("deliverytime/{id}", "DeliveryTimesController@delete");

    Route::get("orders/schedule/intervals", "OrdersScheduleIntervalController@index");
    Route::get("orders/schedule/intervals/{interval_id}", "OrdersScheduleIntervalController@show");
    Route::post("orders/schedule/intervals", "OrdersScheduleIntervalController@store");
    Route::post("orders/schedule/intervals/{interval_id}", "OrdersScheduleIntervalController@update");
    Route::delete("orders/schedule/intervals/{interval_id}", "OrdersScheduleIntervalController@destroy");

    Route::get("coupons", "CouponController@index");
    Route::get("coupons/{coupon}", "CouponController@show");
    Route::post("coupons", "CouponController@store");
    Route::post("coupons/{coupon}", "CouponController@update");
    Route::delete("coupons/{coupon}", "CouponController@destroy");
    Route::get("coupons/{coupon}/users", "CouponController@get_users");
    Route::post("coupons/{coupon}/users", "CouponController@add_user");
    Route::delete("coupons/{coupon}/users", "CouponController@remove_user");
    Route::get("coupons/{coupon}/items", "CouponController@get_items");
    Route::post("coupons/{coupon}/items", "CouponController@add_item");
    Route::delete("coupons/{coupon}/items", "CouponController@remove_item");
    Route::post("itemremove_snapshot", "AdminController@itemremove_snapshot");
    Route::get("charity_address", "AdminController@charity_address")->name("charity_address");

    Route::post("meatmonday", "MeatMondayController@addRemoveProduct");
    Route::get("meatmonday/{cart_id}", "MeatMondayController@showCart");

    #### multi vendor apis
    Route::get("multivendors", "MultivendorController@index");
    Route::get("multivendors/{multivendor}", "MultivendorController@show");
    Route::post("multivendor", "MultivendorController@addRemoveProduct");
    Route::get("multivendor/{cart_id}", "MultivendorController@showCart");
    Route::get("allcarts", "MultivendorController@getCarts");
    Route::get("cartinfo", "MultivendorController@showGlobalCart");

    Route::get("dealsbanners", "DealsBannerController@index");
    Route::get("logs", "DepartmentsConroller@index");
});

// Protect customer routes
Route::middleware("cors", "auth:customers")->group(function() {

    Route::get("logout", function (Request $request) {

        $request->user()->token()->delete();
        return response()->json([ "status" => 1 ], 200);

    });

    Route::get("customers/resendEmailCode","CustomerVerificationController@resend_email_code");

    Route::put("customers", "CustomerUpdateController@store");
    Route::put("customers/password", "CustomerPasswordController@change_password");
    Route::post("customers/verifyEmail","CustomerVerificationController@verify_email");

    Route::get("customers", "CustomersController@show");
    Route::get("customers/addresses", "CustomerAddressController@index");
    Route::get("customers/addresses/{address_id}", "CustomerAddressController@show");
    Route::post("customers/addresses", "CreateCustomerAddressController@store");
    Route::put("customers/addresses/{address_id}", "UpdateCustomerAddressController@update");
    Route::delete("customers/addresses/{address_id}", "CustomerAddressController@delete");

    Route::get("customerbundles", "CustomerBundleController@index")->name("bundle_details");
    Route::put("customerbundles/update/{bundle}", "CustomerBundleController@update_customer_id")->name("update_customerbundleid");
    Route::post("delete_customerbundle", "CustomerBundleController@delete")->name("delete_customerbundle");

    Route::post("checkout", "OrdersController@store");
    Route::get("checkout", "OrdersController@index")->name("delivery_details");
    Route::get("checkout/{order_reference}", "OrdersController@show")->name("order_summary");
    
    Route::get("orders", "OrdersController@allorders");
    Route::post("orders/reorder", "ReorderController@reorder");
    Route::get("orders/schedule", "OrdersScheduleController@index");
    Route::post("orders/schedule", "OrdersScheduleController@store");
    Route::delete("orders/schedule", "OrdersScheduleController@destroy");

    Route::get("wishlist", "WishlistController@index")->name("wishlist");
    Route::post("wishlist", "WishlistController@store")->name("create_wishlist");
    Route::post("wishlist/delete", "WishlistController@destroy")->name("delete_wishlist");

    Route::post("feedback", "FeedbackController@store");

    Route::get("wallet", "WalletController@show")->name("wallet");
    Route::get("wallettransactions", "WalletController@index")->name("wallet_transactions");
    Route::post("redeme", "WalletController@store")->name("redeme_wallet");

    Route::post("meatmonday/checkout", "MeatMondayController@checkout");
    Route::post("multivendor/checkout", "MultivendorController@checkout");

});

# Pure REST API

Route::prefix("rest")->group(function () {

    Route::namespace("\App\Http\Controllers\Rest")->group(function() {

        Route::get("states", "StatesController@index");
        Route::get("states/{id}", "Rest\StatesController@show");

        Route::get("brands", "BrandsController@index");
        Route::get("brands/{id}", "Rest\BrandsController@show");

        Route::get("countries", "CountriesController@index");
        Route::get("countries/{id}", "CountriesController@show");

        Route::get("departments", "DepartmentsConroller@index");
        Route::get("departments/{id}", "DepartmentsConroller@show");

        Route::get("categories", "CategoriesController@index");
        Route::get("categories/{id}", "CategoriesController@show");

        Route::get("subcategories", "SubcategoriesController@index");
        Route::get("subcategories/{id}", "SubcategoriesController@show");

        Route::get("vendors", "VendorsController@index");
        Route::get("vendors/{id}", "VendorsController@show");

        Route::get("metric_units", "MetricUnitsController@index");
        Route::get("metric_units/{unit_id}", "MetricUnitsController@show");

        Route::get("communities", "CommunitiesController@index");
        Route::get("communities/{id}", "CommunitiesController@show");

        Route::get("bundles/categories", "BundlesCategoriesController@index");
        Route::get("bundles/categories/{id}", "BundlesCategoriesController@show");

        Route::get("bundles", "BundlesController@index");
        Route::get("bundles/{bundle_id}", "BundlesController@show");

    });

});
