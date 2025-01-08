<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Query\Query;
use App\Models\AgriNews\AgriNews;
use App\Models\UserRoleMapping;
use App\Models\CropDiseaseDetection\CropDiseaseDiagnosedDtls;
use App\Models\Appmaster\CropDisease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;
use Exception;
use Illuminate\Support\Facades\Http;
use App\Models\FixedMasters\District;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        try {
            $distMaster = District::orderBy('district_name')->get();
            $digeaseMaster = CropDisease::orderBy('disease_name')->get();
            $todays_date = Carbon::now();
            $date_last_30_days = Carbon::now()->subDays(30);
            $CityNameResponse = null;
            $DistrictNameResponse = null;
            $lastDiagnosedDiseaseName = null;
            $lastdDiseaseDiagnosedOn = null;
            $lat = null;
            $lon = null;
            $countModerator = UserRoleMapping::where('role_id', 'M')->orWhere('role_id', 'CM')->count();
            $countAgriExpert = UserRoleMapping::where('role_id', 'AE')->count();
            // $jsonFromDataSetFile = json_decode(file_get_contents(storage_path() . "\app\public\DataSetforDashboard_countDiseaseWise.json"));
            // $districtWisejsonDataFromDataSetFile = json_decode(file_get_contents(storage_path() . "\app\public\DataSetforDashboard.json"));

            $jsonFromDataSetFile = json_decode(file_get_contents(storage_path() . "/app/public/DataSetforDashboard_countDiseaseWise.json"));
            $districtWisejsonDataFromDataSetFile = json_decode(file_get_contents(storage_path() . "/app/public/DataSetforDashboard.json"));
            $dataSetCountDistrictWiseDiseaseWise = json_decode(file_get_contents(storage_path() . "/app/public/DataSetCountDistrictWiseDiseaseWise.json"));

            $lastDiagnosedDisease = DB::table('ag_disease_diagnose_dtls')
                ->select(
                    'ag_disease_diagnose_dtls.disease_diagnosed_cd',
                    'ag_disease_diagnose_dtls.disease_cd',
                    'lat',
                    'lon',
                    'requested_on',
                    'locality_name',
                    'district_name',
                    'district_cd',
                    'ag_crop_disease_master.disease_name'
                )
                ->join('ag_crop_disease_master', 'ag_crop_disease_master.disease_cd', '=', 'ag_disease_diagnose_dtls.disease_cd')
                ->orderBy('requested_on', 'desc')->first();
            try {
                if ($lastDiagnosedDisease) {
                    $CityNameResponse = $lastDiagnosedDisease->locality_name;
                    $DistrictNameResponse = $lastDiagnosedDisease->district_name;
                    $lat = $lastDiagnosedDisease->lat;
                    $lon = $lastDiagnosedDisease->lon;
                    $lastDiagnosedDiseaseName = $lastDiagnosedDisease->disease_name;
                    $lastdDiseaseDiagnosedOn = date("d-m-Y", strtotime($lastDiagnosedDisease->requested_on));
                }
                if (($CityNameResponse == "" || $CityNameResponse == null) && $lat != null) {
                    $distNLocalityNameResponse = Http::get('http://43.205.45.246:8082/getDistrict_and_lcality_name_from_MapBoxAPI?lat=' . $lastDiagnosedDisease->lat . '&lon=' . $lastDiagnosedDisease->lon);
                    $CityNameResponse = $distNLocalityNameResponse["locality_name"];
                    $DistrictNameResponse = $distNLocalityNameResponse["district_name"];
                    if ($distNLocalityNameResponse["locality_name"] == "NA")
                        $CityNameResponse = Http::get('http://43.205.45.246:8082/getCityNameFromGeoPy?lat=' . $lat . '&lon=' . $lon);
                    // $CityNameResponse = Http::get('http://13.235.161.142:8080/getCityNameFromGeoPy?lat=' . $lastDiagnosedDisease->lat . '&lon=' . $lastDiagnosedDisease->lon);
                    if ($distNLocalityNameResponse["district_name"] == "NA")
                        $DistrictNameResponse = Http::get('http://43.205.45.246:8082/getDistrictNamesFromGeoPyWithOutToken?lat=' . $lastDiagnosedDisease->lat . '&lon=' . $lastDiagnosedDisease->lon);
                    // $DistrictNameResponse = Http::get('http://13.235.161.142:8080/getDistrictNamesFromGeoPyWithOutToken?lat=' . $lastDiagnosedDisease->lat . '&lon=' . $lastDiagnosedDisease->lon);
                }



                Log::info("last diagoned disease : " . $lastDiagnosedDiseaseName);
                Log::info("last diagnosed disease location Name : " . $CityNameResponse);
                Log::info("last diagnosed disease District  : " . $DistrictNameResponse);

            } catch (Exception $e) {
                Log::error($e->getMessage());
                Log::error($e->getCode());
                $CityNameResponse = "Sonapur";
                $DistrictNameResponse = "Kamrup Metro";
            }
            // Log::info($districtWiseGeoJsonData);

            $districtWiseAllDiseaseJsonData = (file_get_contents(storage_path() . "/app/public/DataSetOfAllDiseasesDistrictWise.json"));

            $arr = json_decode($districtWiseAllDiseaseJsonData);
            $disease_dtls = new stdClass();
            $diseaseWiseCountArr = array();
            foreach ($arr as $item) {
                $disease_dtls = $item->disease_dtls;
                array_push($diseaseWiseCountArr, $disease_dtls);
            }

            // Load Query Count Data start
            $totalQueryCount = Query::count('query_id');
            $moderatedQueryCount = Query::where('is_moderated', 1)->count();
            $answeredQueryCount = Query::where('is_answered', 1)->count();

            // $response = array(array('Queries Received'=>$totalQueryCount, 'Queries Moderated' => $moderatedQueryCount,
            //     'Queries Answered' => $answeredQueryCount));
            $queryData = array(
                array('x' => 'Queries Received', 'y' => $totalQueryCount),
                array("x" => 'Queries Moderated', "y" => $moderatedQueryCount),
                array("x" => 'Queries Answered', "y" => $answeredQueryCount)
            );


            Log::info(json_encode($queryData));
            // Load Query Count Data End


            ////Get Data for pi Chart -- Start
            $totalFAQ = AgriNews::where('news_catg_cd', 'F')->count();
            $totalSchema = AgriNews::where('news_catg_cd', 'S')->count();
            $totalGeneral = AgriNews::where('news_catg_cd', 'G')->count();
            $piChartData = array($totalGeneral, $totalSchema, $totalFAQ);
            // Log::info($piChartData);
            // return response()->json($response);
            ////Get Data for pi Chart -- End


            $DataSet_count_diseaseWise_for_bar_chart = json_decode(file_get_contents(storage_path() . "/app/public/DataSetforDashboard_countDiseaseWise_for_Chart.json"));
            return view('admin.dashboard', [
                'moderators' => $countModerator,
                'moderatorThisMonth' => 0,
                'agriExperts' => $countAgriExpert,
                'agriExpertThisMonth' => 0,
                'jsonFromDataSetFile' => $jsonFromDataSetFile,
                "districtWiseAllDiseaseJsonData" => $districtWiseAllDiseaseJsonData,
                'districtWisejsonData' => $districtWisejsonDataFromDataSetFile,
                'lastDiagnosedDiseaseAtlocation' => $CityNameResponse,
                'lastDiagnosedDiseaseAtDistrict' => $DistrictNameResponse,
                'lastDiagnosedDiseaseName' => $lastDiagnosedDiseaseName,
                'lastDiagnosedOn' => $lastdDiseaseDiagnosedOn,
                'dataSetCountDistrictWiseDiseaseWise' => $dataSetCountDistrictWiseDiseaseWise,
                'diseaseWiseCountArr' => json_encode($diseaseWiseCountArr),
                "query_count_data" => json_encode($queryData),
                "piChartData" => json_encode($piChartData),
                "DataSet_count_diseaseWise_for_bar_chart" => json_encode($DataSet_count_diseaseWise_for_bar_chart),
                'distMaster' => $distMaster,
                'digeaseMaster' => $digeaseMaster,
                'todays_date' => $todays_date,
                'date_last_30_days' => $date_last_30_days
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error(message: $e->getCode());
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('welcome');
        }
    }

    public function getDataForDistrictWiseDiseaseWiseForMap(Request $request)
    {
        try {
            Log::info("DashboardController: Inside getDataForDistrictWiseDiseaseWiseForMap");
            DB::enableQueryLog();
            $dist_cd = $request->sel_dist_cd;
            $disease_cd = $request->sel_disease_cd;
            $fr_dt = $request->frm_date;
            $to_dt = $request->to_date;

            if ($dist_cd != null) {
                $baseQuery = DB::table('ag_disease_diagnose_dtls AS disg_diag')
                    ->select(
                        'disg_diag.district_cd',
                        'dist_m.district_name',
                        'disg_diag.disease_cd',
                        'disg_m.disease_name',
                        DB::raw('count(*) as total')
                    )
                    ->join('tbl_district_master AS dist_m', 'dist_m.district_cd', '=', 'disg_diag.district_cd')
                    ->join('ag_crop_disease_master AS disg_m', 'disg_m.disease_cd', '=', 'disg_diag.disease_cd')
                    ->where("disg_diag.disease_cd", '!=', "35")
                    ->whereNotIn("disg_diag.disease_cd", ['24', '25', '26', '27'])
                    ->whereNotNull('disg_diag.district_cd')
                    ->whereNotNull('disg_diag.disease_cd')
                    ->groupBy('disg_diag.disease_cd')
                    ->groupBy('disg_diag.district_cd')
                    ->groupBy('disg_m.disease_name')
                    ->groupBy('dist_m.district_name')
                    ->orderby('disg_diag.district_cd', 'asc')
                    ->orderby('disg_m.disease_name', 'asc');

                if ($dist_cd != "A")
                    $baseQuery->where("disg_diag.district_cd", '=', "$dist_cd");

                if ($disease_cd != "A")
                    $baseQuery->where("disg_diag.disease_cd", '=', "$disease_cd");

                if ($fr_dt != "" & $to_dt != "")
                    $baseQuery->whereBetween("disg_diag.requested_on", [$fr_dt, $to_dt]);



                $diseaseDiagnosedCountDistrictWiseDiseaseWise = $baseQuery->get();
            }


            $queries = DB::getQueryLog();
            Log::info(json_encode($queries));
            $arrDiseaseDiagnosedCountDistrictWiseDiseaseWise = array();

            $arr_disg_dtls = array();

            for ($i = 0; $i < count($diseaseDiagnosedCountDistrictWiseDiseaseWise); $i = $i + 1) {
                $districtWiseDiseaseWiseCountJson = new stdClass();
                $old_dist_cd = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$i]->district_cd;
                $old_disg_cd = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$i]->disease_cd;
                $old_dist_name = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$i]->district_name;
                $j = $i;
                for (; $j < count($diseaseDiagnosedCountDistrictWiseDiseaseWise); $j = $j + 1) {

                    $new_dist_cd = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$j]->district_cd;
                    $new_disg_cd = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$j]->disease_cd;

                    $disg_dtls_json = new stdClass();
                    $disg_dtls_json->disease_name = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$j]->disease_name;
                    $disg_dtls_json->total = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$j]->total;
                    Log::info("old_dist_cd: " . $old_dist_cd);
                    Log::info("new_dist_cd: " . $new_dist_cd);
                    if ($old_dist_cd == $new_dist_cd) {
                        Log::info("Milise");
                        array_push($arr_disg_dtls, $disg_dtls_json);
                    } else {
                        $i = $j - 1;
                        break;
                    }

                }

                $districtWiseDiseaseWiseCountJson->district_cd = $old_dist_cd;
                $districtWiseDiseaseWiseCountJson->district_name = $old_dist_name;
                $districtWiseDiseaseWiseCountJson->disease_dtls = $arr_disg_dtls;
                array_push($arrDiseaseDiagnosedCountDistrictWiseDiseaseWise, $districtWiseDiseaseWiseCountJson);

                $arr_disg_dtls = array();


            }
            Log::info('xxx: ' . json_encode($arrDiseaseDiagnosedCountDistrictWiseDiseaseWise));
            return $arrDiseaseDiagnosedCountDistrictWiseDiseaseWise;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

    }
    // public function getDiseaseWiseCountFromJsonDataSet()
    // {
    //     $jsonFromDataSetFile = json_decode(file_get_contents(storage_path() . "/app/public/DataSetforDashboard_countDiseaseWise_for_Chart.json"));
    //     return $jsonFromDataSetFile;
    // }
    // public function loadJSONData(Request $request)
    // {
    //     $totalQueryCount = Query::count('query_id');
    //     $moderatedQueryCount = Query::where('is_moderated', 1)->count();
    //     $answeredQueryCount = Query::where('is_answered', 1)->count();

    //     // $response = array(array('Queries Received'=>$totalQueryCount, 'Queries Moderated' => $moderatedQueryCount,
    //     //     'Queries Answered' => $answeredQueryCount));
    //     $response = array(
    //         array('x' => 'Queries Received', 'y' => $totalQueryCount),
    //         array("x" => 'Queries Moderated', "y" => $moderatedQueryCount),
    //         array("x" => 'Queries Answered', "y" => $answeredQueryCount)
    //     );


    //     Log::info(json_encode($response));
    //     return response()->json($response);
    // }


    // public function loadJSONDataForAgriNews(Request $request)
    // {
    //     try {
    //         $totalFAQ = AgriNews::where('news_catg_cd', 'F')->count();
    //         $totalSchema = AgriNews::where('news_catg_cd', 'S')->count();
    //         $totalGeneral = AgriNews::where('news_catg_cd', 'G')->count();

    //         // $response = array(array('Queries Received'=>$totalQueryCount, 'Queries Moderated' => $moderatedQueryCount,
    //         //     'Queries Answered' => $answeredQueryCount));
    //         $response = array($totalGeneral, $totalSchema, $totalFAQ);


    //         Log::info($response);
    //         return response()->json($response);
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage());
    //     }
    // }

    // public function loadDistrictWiseGeoJsonData(Request $request)
    // {
    //     try {
    //         // $districtWiseGeoJsonData = (file_get_contents(storage_path() . "\app\public\DistrictWiseGeoJsonData_xxx.json"));
    //         $districtWiseGeoJsonData = (file_get_contents(storage_path() . "/app/public/DistrictWiseGeoJsonData_xxx.json"));

    //         return response()->json($districtWiseGeoJsonData);
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage());
    //     }
    // }


    // public function loadDataSetOfAllDiseasesDistrictWise(Request $request)
    // {
    //     try {
    //         $dist_cd = $request->dist_cd;
    //         // $districtWiseAllDiseaseJsonData = (file_get_contents(storage_path() . "\app\public\DataSetOfAllDiseasesDistrictWise.json"));
    //         $districtWiseAllDiseaseJsonData = (file_get_contents(storage_path() . "/app/public/DataSetOfAllDiseasesDistrictWise.json"));

    //         $arr = json_decode($districtWiseAllDiseaseJsonData);
    //         $disease_dtls = new stdClass();
    //         foreach ($arr as $item) {
    //             $d_cd = $item->district_cd; //etc
    //             if ($dist_cd == $d_cd)
    //                 $disease_dtls = $item->disease_dtls;
    //         }
    //         return response()->json($disease_dtls);
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage());
    //     }
    // }

    // public function loadDataSetOfAllDiseases(Request $request)
    // {
    //     try {
    //         Log::info("Inside loadDataSetOfAllDiseases Method");
    //         // $districtWiseAllDiseaseJsonData = (file_get_contents(storage_path() . "\app\public\DataSetOfAllDiseasesDistrictWise.json"));
    //         $districtWiseAllDiseaseJsonData = (file_get_contents(storage_path() . "/app/public/DataSetOfAllDiseasesDistrictWise.json"));

    //         $arr = json_decode($districtWiseAllDiseaseJsonData);
    //         $disease_dtls = new stdClass();
    //         $diseaseWiseCountArr = array();
    //         foreach ($arr as $item) {
    //             $disease_dtls = $item->disease_dtls;
    //             array_push($diseaseWiseCountArr, $disease_dtls);
    //         }

    //         return response()->json($diseaseWiseCountArr);
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage());
    //     }
    // }


}