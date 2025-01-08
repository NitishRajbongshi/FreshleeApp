<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DatasetForDashboardController;
use App\Http\Controllers\Admin\AppLinkDeleteUser;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AllCropDetailsController;
use App\Http\Controllers\Admin\CropDetailsVarietyController;
use App\Http\Controllers\Admin\CropDetailsProtectionController;
use App\Http\Controllers\Admin\CropDetailsSymptomController;
use App\Http\Controllers\Admin\ReviewCropImageController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\AgriMedicianlProductController;
use App\Http\Controllers\Moderator\ModeratorProfileController;
use App\Http\Controllers\AgriExpert\AgriExpertProfileController;
use App\Http\Controllers\AgCropImageUploadController;
use App\Http\Controllers\AgriNews\AgriNewsCategoryController;
use App\Http\Controllers\AgriNews\AgriNewsController;
use App\Http\Controllers\ApplicationMaster\CropDiseaseController;
use App\Http\Controllers\ApplicationMaster\CropTypeController;
use App\Http\Controllers\ApplicationMaster\CropInformationController;
use App\Http\Controllers\ApplicationMaster\SuitabilityController;
use App\Http\Controllers\ApplicationMaster\SymptomController;
use App\Http\Controllers\ApplicationMaster\RecommendationController;
use App\Http\Controllers\FreshleeMarket\ItemReportController;
use App\Http\Controllers\FreshleeMaster\ItemController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\QueryHandler\QueryCategoryController;
use App\Http\Controllers\QueryHandler\QueryController;
use App\Http\Controllers\UtilsController;
use App\Models\AgriNews\AgriNewsCategory;
use App\Http\Controllers\MIS\ReportController;
use App\Http\Controllers\Order\UserOrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
Route::get('/external_images/{path}', function ($path) {

    $filePath = Storage::disk('external')->path($path);

    if (!file_exists($filePath)) {
        abort(404);
    }

    return response()->file($filePath);
})->where('path', '.*');

// Route::get('getencryptedpass/{pass}', [UtilsController::class, 'getEncryptedPassword'])->name('getencrypted.password');
Route::get('', [AuthController::class, 'login'])->name('auth.login');
Route::post('', [AuthController::class, 'loginUser'])->name('auth.login_post');
Route::get('logout', [AuthController::class, 'logoutUser'])->name('auth.logout');
Route::post('attemptlogin', [UtilsController::class, 'attemptLogin'])->name('attemptLogin');
Route::middleware('auth')->post('change-password', [AuthController::class, 'updatePassword'])->name('auth.update.password');
Route::get('profile', [ProfileController::class, 'profile'])->name('admin.profile');



// Route::match(['get', 'post'], 'register', [AuthController::class, 'register'])->name('auth.register');

// JSON Routes for admin dashboard
Route::middleware('auth')->get('adminjson', [DashboardController::class, 'loadJSONData'])->name('admin.jsondata');
Route::middleware('auth')->get('adminjsonAgriNews', [DashboardController::class, 'loadJSONDataForAgriNews'])->name('admin.jsondataAgriNews');
Route::get('dataSetJsonForDashboard', [DatasetForDashboardController::class, 'createDataSetforDashboard'])->name('admin.dataSetJsonForDashboard');
Route::middleware('auth')->get('loadDistrictWiseGeoJsonData', [DashboardController::class, 'loadDistrictWiseGeoJsonData'])->name('admin.loadDistrictWiseGeoJsonData');
Route::middleware('auth')->get('loadDataSetOfAllDiseasesDistrictWise', [DashboardController::class, 'loadDataSetOfAllDiseasesDistrictWise'])->name('admin.loadDataSetOfAllDiseasesDistrictWise');
Route::middleware('auth')->get('loadDataSetOfAllDiseases', [DashboardController::class, 'loadDataSetOfAllDiseases'])->name('loadDataSetOfAllDiseases');
Route::middleware('auth')->post('getDataForDistrictWiseDiseaseWiseForMap', [DashboardController::class, 'getDataForDistrictWiseDiseaseWiseForMap'])->name('getDataForDistrictWiseDiseaseWiseForMap');

Route::group(['prefix' => 'appLink'], function () {
    Route::get('deleteUser', [AppLinkDeleteUser::class, 'deleteUser'])->name('admin.deleteUser');
});


Route::middleware('auth')->get('adminjson', [DashboardController::class, 'loadJSONData'])->name('admin.dashboard1');
Route::middleware('auth')->get('dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
Route::middleware('auth')->get('getDiseaseWiseCount', [DashboardController::class, 'getDiseaseWiseCountFromJsonDataSet'])->name('getDiseaseWiseCount');

Route::middleware('auth')->get('users', [UserController::class, 'users'])->name('admin.users');
Route::middleware('auth')->match(['get', 'post'], 'create-user', [UserController::class, 'create'])->name('admin.users.create');
Route::middleware('auth')->get('edit-user/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
Route::middleware('auth')->post('edit-user/{id}', [UserController::class, 'edit_save'])->name('admin.users.edit_save');
Route::middleware('auth')->post('user/changestatus', [UserController::class, 'change_staus'])->name('admin.user.changestatus');

Route::middleware('auth')->get('usermanagement', [UserManagementController::class, 'usermanagement'])->name('admin.usermanagement');
// Route::post('user/role/update', [UserManagementController::class, 'updateUserRoles'])->name('user.role.update');
Route::middleware('auth')->match(['get', 'post'], 'update-user-role', [UserManagementController::class, 'updateUserRoles'])->name('admin.usermanagement.usermanagement.updateUserRoles');

Route::middleware('auth')->get('allcropdetails', [AllCropDetailsController::class, 'allcropdetails'])->name('admin.allcropdetails');
Route::get('/admin/crop-names', [AllCropDetailsController::class, 'getCropNames']);
Route::get('/admin/suitability-details', [AllCropDetailsController::class, 'getSuitabilityDetails']);
Route::post('/allcropdetails/saveSuitabilityDetails', [AllCropDetailsController::class, 'saveSuitabilityDetails'])->name('admin.allcropdetails.allcropdetails.saveSuitabilityDetails');


Route::middleware('auth')->get('cropvarietydetails', [CropDetailsVarietyController::class, 'cropvarietydetails'])->name('admin.cropvarietydetails');
Route::get('/admin/crop-names', [CropDetailsVarietyController::class, 'getCropNames']);
Route::middleware('auth')->match(['get', 'post'], 'create-variety', [CropDetailsVarietyController::class, 'create'])->name('admin.cropvarietydetails.createvariety');
Route::get('/admin/crop-varieties', [CropDetailsVarietyController::class, 'getCropVarieties']);
Route::put('/cropvarietydetails/update', [CropDetailsVarietyController::class, 'update'])->name('admin.cropvarietydetails.update');
Route::delete('/cropvarietydetails/delete', [CropDetailsVarietyController::class, 'destroy'])->name('admin.cropvarietydetails.cropvarietydetails.destroy');
// Route::post('admin/cropvarietydetails/createvariety', [CropDetailsVarietyController::class, 'create'])->name('admin.cropvarietydetails.createvariety');

Route::middleware('auth')->get('cropprotectiondetails', [CropDetailsProtectionController::class, 'cropprotectiondetails'])->name('admin.cropprotectiondetails');
Route::get('/admin/crop-nam', [CropDetailsProtectionController::class, 'getCropNam']);
Route::get('/admin/diseases', [CropDetailsProtectionController::class, 'getDiseases'])->name('admin.cropprotectiondetails.getDiseases');
Route::get('/admin/get-tech-names-by-product-type', [CropDetailsProtectionController::class, 'getTechnicalNameByProductType'])->name('admin.cropprotectiondetails.getTechnicalNameByProductType');
Route::get('/admin/get-trade-names-by-product-type', [CropDetailsProtectionController::class, 'getTradeNamesByTechnicalCodes'])->name('admin.cropprotectiondetails.getTradeNamesByTechnicalCodes');
Route::post('/cropprotectiondetails/saveCropProtectionDetails', [CropDetailsProtectionController::class, 'saveCropProtectionDetails'])->name('admin.cropprotectiondetails.cropprotectiondetails.saveCropProtectionDetails');
Route::get('/admin/crop-disease', [CropDetailsProtectionController::class, 'getCropDisease']);
Route::put('/cropprotectiondetails/update', [CropDetailsProtectionController::class, 'update'])->name('admin.cropprotectiondetails.cropprotectiondetails.update');
Route::delete('/cropprotectiondetails/delete', [CropDetailsProtectionController::class, 'destroy'])->name('admin.cropprotectiondetails.cropprotectiondetails.destroy');
Route::middleware('auth')->match(['get', 'post'], 'create-protection', [CropDetailsProtectionController::class, 'create'])->name('admin.cropprotectiondetails.createprotection');


Route::middleware('auth')->get('cropsymptomdetails', [CropDetailsSymptomController::class, 'cropsymptomdetails'])->name('admin.cropsymptomdetails');
Route::get('/admin/crop-name', [CropDetailsSymptomController::class, 'getCrop']);
Route::get('/admin/disease', [CropDetailsSymptomController::class, 'getDisease'])->name('admin.cropsymptomdetails.getDisease');
Route::get('/admin/crop-disease-symptom', [CropDetailsSymptomController::class, 'getCropDiseaseSymptom']);
Route::middleware('auth')->match(['get', 'post'], 'create-symptom', [CropDetailsSymptomController::class, 'create'])->name('admin.cropsymptomdetails.createsymptom');
Route::put('/cropsymptomdetails/update', [CropDetailsSymptomController::class, 'update'])->name('admin.cropsymptomdetails.cropsymptomdetails.update');
Route::delete('/cropsymptomdetails/delete', [CropDetailsSymptomController::class, 'destroy'])->name('admin.cropsymptomdetails.cropsymptomdetails.destroy');


Route::middleware('auth')->get('reviewcropimage', [ReviewCropImageController::class, 'reviewcropimage'])->name('admin.reviewcropimage');
Route::get('/image/{filename}', [ImageController::class, 'show'])->name('image.show');
Route::post('/admin/review-crop-image/correct', action: [ReviewCropImageController::class, 'saveCorrectImage'])->name('save.correct.image');

Route::group(['prefix' => 'master', 'middleware' => 'auth'], function () {
    Route::get('users', [UserController::class, 'users'])->name('admin.users');
    Route::match(['get', 'post'], 'create-user', [UserController::class, 'create'])->name('admin.users.create');
    Route::get('edit-user/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('edit-user/{id}', [UserController::class, 'edit_save'])->name('admin.users.edit_save');
    Route::post('user/changestatus', [UserController::class, 'change_staus'])->name('admin.user.changestatus');

    Route::get('rolemanager', [RoleController::class, 'rolemanager'])->name('admin.rolemanager');
    Route::post('setrole', [RoleController::class, 'setRole'])->name('admin.role.set');

    //master/designation routes
    Route::get('designation', [DesignationController::class, 'index'])->name('admin.designation');
    Route::post('designation', [DesignationController::class, 'createDesignation'])->name('admin.designation_post');
    Route::post('delete/designation', [DesignationController::class, 'delete'])->name('admin.designation.delete');
    Route::post('edit/designation', [DesignationController::class, 'editDesignation'])->name('admin.designation.edit');

    //master/department routes
    Route::get('department', [DepartmentController::class, 'index'])->name('admin.department');
    Route::post('department', [DepartmentController::class, 'createDepartment'])->name('admin.department_post');
    Route::post('delete/department', [DepartmentController::class, 'delete'])->name('admin.department.delete');
    Route::post('edit/department', [DepartmentController::class, 'editDepartment'])->name('admin.department.edit');

    //master/role routes
    Route::get('role', [RoleController::class, 'index'])->name('admin.role');
    Route::post('role', [RoleController::class, 'createRole'])->name('admin.role_post');
    Route::post('role/edit', [RoleController::class, 'editRole'])->name('admin.role.edit');

    //master/office routes
    Route::get('office', [OfficeController::class, 'index'])->name('admin.office');
    Route::post('office', [OfficeController::class, 'createOffice'])->name('admin.office_post');
    Route::post('office/edit', [OfficeController::class, 'editOffice'])->name('admin.office.edit');
    // admin/user order
    Route::get("order/reports", [ItemReportController::class, 'index'])->name('admin.user.order');
    Route::post("order/delivery/update", [ItemReportController::class, 'updateDeliveryStatus'])->name('admin.order.delivery.update');
    Route::post("order/history", [ItemReportController::class, 'history'])->name('admin.order.history');

    // admin/freshlee-master
    Route::get('freshlee/master/items', [ItemController::class, 'index'])->name('admin.freshlee.master.item');
    Route::get('freshlee/master/items/create', [ItemController::class, 'create'])->name('admin.freshlee.master.item.create');
    Route::post('freshlee/master/items/create', [ItemController::class, 'store']);
});


Route::group(['prefix' => 'application-master', 'middleware' => 'auth'], function () {
    Route::get('cropinformation', [CropInformationController::class, 'cropinformation'])->name('admin.appmaster.cropinformation');
    Route::put('crops/update', [CropInformationController::class, 'update'])->name('admin.appmaster.crops.update');
    Route::post('crops/check-registry-no', [CropInformationController::class, 'checkRegistryNo'])->name('admin.appmaster.crops.checkRegistryNo');
    Route::middleware('auth')->match(['get', 'post'], 'create-crop', [CropInformationController::class, 'create'])->name('admin.appmaster.createcrop');
    Route::delete('crops/delete', [CropInformationController::class, 'destroy'])->name('admin.appmaster.crops.destroy');


    // application-master/croptype routes
    Route::get('croptype', [CropTypeController::class, 'index'])->name('admin.appmaster.croptype');
    Route::put('croptype/update', [CropTypeController::class, 'update'])->name('admin.appmaster.croptype.update');
    Route::middleware('auth')->match(['get', 'post'], 'create-crop-type', [CropTypeController::class, 'create'])->name('admin.appmaster.createcroptype');
    Route::delete('croptype/delete', [CropTypeController::class, 'destroy'])->name('admin.appmaster.croptype.destroy');


    // application-master/cropdesease routes
    Route::get('cropdisease', [CropDiseaseController::class, 'index'])->name('admin.appmaster.cropdisease');
    Route::put('cropdisease/update', [CropDiseaseController::class, 'update'])->name('admin.appmaster.cropdisease.update');
    Route::middleware('auth')->match(['get', 'post'], 'create-crop-disease', [CropDiseaseController::class, 'create'])->name('admin.appmaster.createcropdisease');
    Route::delete('cropdisease/delete', [CropDiseaseController::class, 'destroy'])->name('admin.appmaster.cropdisease.destroy');


    Route::get('suitability', [SuitabilityController::class, 'index'])->name('admin.appmaster.suitability');
    Route::get('suitability/get-crop-names', [SuitabilityController::class, 'getCropNames'])->name('admin.appmaster.getCropNames');
    Route::put('suitability/update', [SuitabilityController::class, 'update'])->name('admin.appmaster.suitability.update');
    Route::middleware('auth')->match(['get', 'post'], 'create-suitability', [SuitabilityController::class, 'create'])->name('admin.appmaster.createsuitability');
    Route::delete('suitability/delete', [SuitabilityController::class, 'destroy'])->name('admin.appmaster.suitability.destroy');


    Route::get('symptom', [SymptomController::class, 'index'])->name('admin.appmaster.symptom');
    Route::put('symptom/update', [SymptomController::class, 'update'])->name('admin.appmaster.symptom.update');
    Route::middleware('auth')->match(['get', 'post'], 'create-symptom', [SymptomController::class, 'create'])->name('admin.appmaster.createsymptom');
    Route::delete('symptom/delete', [SymptomController::class, 'destroy'])->name('admin.appmaster.symptom.destroy');

    Route::get('recommendation', [RecommendationController::class, 'index'])->name('admin.appmaster.recommendation');
    Route::put('recommendation/update', [RecommendationController::class, 'update'])->name('admin.appmaster.recommendation.update');
    Route::middleware('auth')->match(['get', 'post'], 'create-recommendation', [RecommendationController::class, 'create'])->name('admin.appmaster.createrecommendation');
    Route::delete('recommendation/delete', [RecommendationController::class, 'destroy'])->name('admin.appmaster.recommendation.destroy');
});


Route::middleware('auth')->get('rolemanager', [RoleController::class, 'rolemanager'])->name('admin.rolemanager');
Route::middleware('auth')->post('setrole', [RoleController::class, 'setRole'])->name('admin.role.set');
Route::middleware('auth')->get('filemanager', [AgCropImageUploadController::class, 'view'])->name('admin.filemanager');

Route::middleware('auth')->get('filemanager/upload', function () {
    return view('admin.filemanager.upload');
})->name('admin.filemanager.upload');

Route::group(['prefix' => 'query-manager', 'middleware' => 'auth'], function () {
    Route::get('moderator/queries', [QueryController::class, 'index'])->name('moderator.queries');
    Route::get('moderator/dashboar', [QueryController::class, 'dashboard'])->name('moderator.dashboard');
    // Route::get('moderator/queriestoanswer', [QueryController::class, 'queriesToAnswer'])->name('moderator.queriestoanswer');
    Route::post('moderator/acceptquery', [QueryController::class, 'acceptQuery'])->name('moderator.acceptquery');
    Route::post('moderator/rejectquery', [QueryController::class, 'rejectQuery'])->name('moderator.rejectquery');
    Route::post('moderator/createcategory', [QueryCategoryController::class, 'createCategory'])->name('moderator.createcategory');

    Route::get('moderator/linkparentquery/{id}', [QueryController::class, 'linkParentQuery'])->name('moderator.linkparentquery');
    Route::post('moderator/setparentquery', [QueryController::class, 'setParentQuery'])->name('moderator.setparentquery');
    Route::get('moderator/profile', [ModeratorProfileController::class, 'profile'])->name('moderator.profile');
});

Route::group(['prefix' => 'query-answer', 'middleware' => 'auth'], function () {
    Route::get('agri-expert/dashboard', [QueryController::class, 'agriexpert_dashboard'])->name('agriexpert.dashboard');
    // Route::get('agri-expert/queriestoanswer', [QueryController::class, 'queriesToAnswer'])->name('agriexpert.queriestoanswer');
    Route::get('agri-expert/queryviewer/{category?}', [QueryController::class, 'queriesToAnswer'])->name('agriexpert.queriestoanswer');
    // Route::get('agri-expert/queryviewer/{category?}', [QueryController::class, 'queriesToAnswerByCategory'])->name('agriexpert.queriestoanswerbycategory');
    Route::post('agri-expert/answerquery', [QueryController::class, 'answerQuery'])->name('agriexpert.answerquery');

    Route::get('agri-expert/thread/{id}', [QueryController::class, 'answerThread'])->name('agriexpert.loadthread');
    Route::get('agri-expert/profile', [AgriExpertProfileController::class, 'profile'])->name('agriExpert.profile');
});

Route::group(['prefix' => 'agri-news', 'middleware' => 'auth'], function () {
    Route::get('newsmanager', [AgriNewsController::class, 'index'])->name('agrinews.newsmanager');
    Route::get('categorymanager', [AgriNewsCategoryController::class, 'index'])->name('agrinews.categorymanager');
    Route::post('categorymanager/create', [AgriNewsCategoryController::class, 'createNewCategory'])->name('agrinews.categorymanager.create');
    Route::post('categorymanager/edit', [AgriNewsCategoryController::class, 'editNewsCategory'])->name('agrinews.categorymanager.edit');
    Route::match(['get', 'post'], 'createnews', [AgriNewsController::class, 'createNews'])->name('admin.agrinews.create');
});

//MIS Reports

Route::get('misHomePage', [ReportController::class, 'index'])->name('misHomePage');
Route::post('getMISReport', [ReportController::class, 'getMISReport'])->name('getMISReport');
Route::get('/external_images/{path}', function ($path) {
    // Use the 'external' disk to get the file path
    $filePath = Storage::disk('external')->path($path);

    // Check if the file exists
    if (!file_exists($filePath)) {
        abort(404);
    }

    // Return the file as a response
    return response()->file($filePath);
})->where('path', '.*');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/agri-medicinal-products', [AgriMedicianlProductController::class, 'index'])->name('agri-medicinal-products');
});