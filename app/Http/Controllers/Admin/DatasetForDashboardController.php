<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FixedMasters\District;
use Illuminate\Http\Request;
use App\Models\CropDiseaseDetection\CropDiseaseDiagnosedDtls;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use stdClass;

class DatasetForDashboardController extends Controller
{
   public function createDataSetforDashboard()
   {
      DB::enableQueryLog();
      $diseaseDiagnosedDistrictWiseData = CropDiseaseDiagnosedDtls::with('district')
         // ->select('disease_cd','disease_diagnosed_cd','crop_type_cd','requested_on',)
         ->select('district_cd', DB::raw('count(*) as total'))
         ->whereNotNull("disease_cd")
         ->where("disease_cd", '!=', "35")
         ->whereNotIn("disease_cd", ['24', '25', '26', '27'])
         ->whereNotNull("disease_diagnosed_cd")
         ->orderby('district_cd', 'asc')
         ->groupBy('district_cd')
         ->get();

      $diseaseDiagnosedCountDistrictWiseDiseaseWise = DB::table('ag_disease_diagnose_dtls AS disg_diag')
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
         ->orderby('disg_m.disease_name', 'asc')
         ->get();

      $arrDistrictJson = array();
      foreach ($diseaseDiagnosedDistrictWiseData as $diseaseDistrictWiseItem) {
         $districtWiseCountJson = new stdClass();
         $districtWiseCountJson->district_cd = $diseaseDistrictWiseItem->district_cd;

         $districtWiseCountJson->total = $diseaseDistrictWiseItem->total;
         foreach ($diseaseDistrictWiseItem->district as $districtItem) {
            $districtWiseCountJson->district_center_lon = floatval($districtItem->longitude);
            $districtWiseCountJson->district_center_lat = floatval($districtItem->latitude);
            $districtWiseCountJson->district_name = $districtItem->district_name;
         }
         array_push($arrDistrictJson, $districtWiseCountJson);
      }


      $arrDiseaseDiagnosedCountDistrictWiseDiseaseWise = array();

      $arr_disg_dtls = array();

      for ($i = 0; $i < count($diseaseDiagnosedCountDistrictWiseDiseaseWise); $i = $i + 1) {
         $districtWiseDiseaseWiseCountJson = new stdClass();


         $old_dist_cd = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$i]->district_cd;
         $old_disg_cd = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$i]->disease_cd;
         $old_dist_name = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$i]->district_name;

         for ($j = $i; $j < count($diseaseDiagnosedCountDistrictWiseDiseaseWise); $j = $j + 1) {

            $new_dist_cd = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$j]->district_cd;
            $new_disg_cd = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$j]->disease_cd;

            $disg_dtls_json = new stdClass();
            $disg_dtls_json->disease_name = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$j]->disease_name;
            $disg_dtls_json->total = $diseaseDiagnosedCountDistrictWiseDiseaseWise[$j]->total;

            if ($new_dist_cd == $old_dist_cd) {
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

      $countDiseaseWise = CropDiseaseDiagnosedDtls::with('cropDisease')
         ->select('disease_cd', DB::raw('count(*) as total'))
         ->whereNotNull("disease_cd")
         ->where("disease_cd", '!=', "35")
         ->whereNotIn("disease_cd", ['24', '25', '26', '27'])
         ->whereNotNull("disease_diagnosed_cd")
         ->orderby('disease_cd', 'asc')
         ->groupBy('disease_cd')
         ->get();
      $queries = DB::getQueryLog();
      Log::info(json_encode($queries));
      // dd($queries);
      //  ->pluck('total','disease_cd');
      // ->toArray();
      $arrJson = array();
      $diseaseWiseCountArr = array();
      foreach ($countDiseaseWise as $diseaseItem) {
         $diseaseWiseCountJson = new stdClass();
         $diseaseWiseCountForChartJson = new stdClass();
         $diseaseWiseCountJson->disease_cd = $diseaseItem->disease_cd;
         $diseaseWiseCountJson->total = $diseaseItem->total;

         $diseaseWiseCountForChartJson->y = $diseaseItem->total;


         foreach ($diseaseItem->cropDisease as $diseaseDtls) {
            $diseaseWiseCountJson->disease_name = $diseaseDtls->disease_name;
            $diseaseWiseCountForChartJson->x = $diseaseDtls->disease_name;
         }


         array_push($arrJson, $diseaseWiseCountJson);
         array_push($diseaseWiseCountArr, $diseaseWiseCountForChartJson);
      }




      $diseaseDiagnosedDistrictWiseAndDiseaseWiseData = CropDiseaseDiagnosedDtls::with('district', 'cropDisease')
         // ->select('disease_cd','disease_diagnosed_cd','crop_type_cd','requested_on',)
         ->select('district_cd', 'disease_cd', DB::raw('count(disease_cd) as disease_count'))
         ->whereNotNull("disease_cd")
         ->whereNotNull("disease_diagnosed_cd")
         ->where("disease_cd", '!=', "35")
         ->whereNotIn("disease_cd", ['24', '25', '26', '27'])
         ->orderby('district_cd', 'asc')
         ->groupBy('disease_cd')
         ->groupBy('district_cd')
         ->get();
      $first_dist_cd = "";
      foreach ($diseaseDiagnosedDistrictWiseAndDiseaseWiseData as $districtWiseDiseaseItem) {
         $first_dist_cd = $districtWiseDiseaseItem->district_cd;
         break;
      }
      $arrDistrictWiseDiseaseJson = array();
      // array_push($arrDistrictWiseDiseaseJson,$diseaseDiagnosedDistrictWiseAndDiseaseWiseData->toArray());
      $arrDiseaseName = array();
      $diseaseNameDtls = new stdClass();
      $finalJsonData = array('type' => 'FeatureCollection');
      $arrFeatures = array();
      $counter = 0;
      $total_rec = $diseaseDiagnosedDistrictWiseAndDiseaseWiseData->count();
      $district_name = "";
      // $finalJsonData->type = "FeatureCollection";
      foreach ($diseaseDiagnosedDistrictWiseAndDiseaseWiseData as $districtWiseDiseaseItem) {
         $counter++;
         $districtWiseDiseaseCountJson = new stdClass();
         $dist_cd = $districtWiseDiseaseItem->district_cd;

         if ($first_dist_cd == $dist_cd) {
            // $arrDiseaseName = array();
            $diseaseNameDtls = new stdClass();
            $isSameDist = true;
            foreach ($districtWiseDiseaseItem->cropDisease as $diseaseDtls) {
               array_push($arrDiseaseName, $diseaseDtls->disease_name . "(" . $districtWiseDiseaseItem->disease_count . ")");
            }
            foreach ($districtWiseDiseaseItem->district as $districtItem) {
               $geometryJson = new stdClass();
               $propertiesJson = new stdClass();

               $geometryJson->type = 'point';
               $geometryJson->coordinates = array(floatval($districtItem->longitude), floatval($districtItem->latitude));
               $propertiesJson->title = 'Disease Data for District';
               $propertiesJson->description = 'Disease Data for District : ' . $districtItem->district_name;

               $districtWiseDiseaseCountJson->type = 'Feature';
               $districtWiseDiseaseCountJson->district_name = $districtItem->district_name;
               $districtWiseDiseaseCountJson->district_cd = $districtWiseDiseaseItem->district_cd;
               $districtWiseDiseaseCountJson->geometry = $geometryJson;
               $districtWiseDiseaseCountJson->properties = $propertiesJson;
               $districtWiseDiseaseCountJson->disease_names = $arrDiseaseName;
               array_push($arrFeatures, $districtWiseDiseaseCountJson);
            }
         } else {
            $first_dist_cd = $dist_cd;
            array_push($arrDistrictWiseDiseaseJson, $districtWiseDiseaseCountJson);
            $arrDiseaseName = array();
            $diseaseNameDtls = new stdClass();
            foreach ($districtWiseDiseaseItem->cropDisease as $diseaseDtls) {
               array_push($arrDiseaseName, $diseaseDtls->disease_name . "(" . $districtWiseDiseaseItem->disease_count . ")");
            }
            foreach ($districtWiseDiseaseItem->district as $districtItem) {
               $geometryJson = new stdClass();
               $propertiesJson = new stdClass();

               $geometryJson->type = 'point';
               $geometryJson->coordinates = array(floatval($districtItem->longitude), floatval($districtItem->latitude));
               $propertiesJson->title = 'Disease Data for District';
               $propertiesJson->description = 'Disease Data for District : ' . $districtItem->district_name;



               $districtWiseDiseaseCountJson->district_name = $districtItem->district_name;
               $districtWiseDiseaseCountJson->district_cd = $districtWiseDiseaseItem->district_cd;
               $districtWiseDiseaseCountJson->geometry = $geometryJson;
               $districtWiseDiseaseCountJson->properties = $propertiesJson;
               $districtWiseDiseaseCountJson->disease_names = $arrDiseaseName;
               array_push($arrFeatures, $districtWiseDiseaseCountJson);
            }
         }
         if ($counter == $total_rec) {
            array_push($arrDistrictWiseDiseaseJson, $districtWiseDiseaseCountJson);
         }
      }
      // array_push($arrDistrictWiseDiseaseJson,$arrDiseaseName);

      Storage::disk('public')->put('DataSetforDashboard.json', json_encode($arrDistrictJson));
      Storage::disk('public')->put('DataSetCountDistrictWiseDiseaseWise.json', json_encode($arrDiseaseDiagnosedCountDistrictWiseDiseaseWise));
      Storage::disk('public')->put('DataSetforDashboard_countDiseaseWise.json', json_encode($arrJson));
      Storage::disk('public')->put('DataSetforDashboard_countDiseaseWise_for_Chart.json', json_encode($diseaseWiseCountArr));
      $finalJsonData['features'] = $arrFeatures;
      Storage::disk('public')->put('DistrictWiseGeoJsonData_xxx.json', json_encode($finalJsonData));
      // return [$arrDistrictJson , $arrJson];
      // return 'Success';



      $allDistricts = District::select('district_cd', 'district_name')
         ->where("state_cd", '=', "1")
         ->orderby('district_cd', 'asc')
         ->get();
      $arrDistricts = array();
      $finalArrdiseasedtlsDistWise = array();
      foreach ($allDistricts as $all_item) {
         $arrdiseasedtlsDistWise = array();
         $diseaseDtls = CropDiseaseDiagnosedDtls::with('district', 'cropDisease')
            ->select(
               'district_cd',
               'disease_cd',
               'lat',
               'lon',
               'district_name',
               'locality_name',
               'requested_on'
            )
            ->where("district_cd", '=', $all_item->district_cd)
            ->where("disease_cd", '!=', "35")
            ->whereNotIn("disease_cd", ['24', '25', '26', '27'])
            ->orderby('disease_cd', 'asc')
            ->get();
         foreach ($diseaseDtls as $item) {
            $arrdiseasedtls = array();
            $disease_name = array();
            foreach ($item->cropDisease as $disease_master)
               $arrdiseasedtls = array(
                  "disease_name" => $disease_master->disease_name,
                  "disease_cd" => $item->disease_cd,
                  "latLon" => array(floatval($item->lon), floatval($item->lat)),
                  "lat" => $item->lat,
                  "lon" => $item->lon,
                  "district_name" => $all_item->district_name,
                  "locality_name" => $item->locality_name,
                  "date" => $item->requested_on,
               );
            // $disease_name= array("disease_name" => $disease_master->disease_name);

            // array_push($arrdiseasedtls, $disease_name);
            array_push($arrdiseasedtlsDistWise, $arrdiseasedtls);
         }
         if ($diseaseDtls->count() > 0)
            array_push(
               $finalArrdiseasedtlsDistWise,
               array(
                  "district_cd" => $all_item->district_cd,
                  "district_name" => $all_item->district_name,
                  "disease_dtls" => $arrdiseasedtlsDistWise
               )
            );
      }

      Storage::disk('public')->put('DataSetOfAllDiseasesDistrictWise.json', json_encode($finalArrdiseasedtlsDistWise));
      // return get_class($diseaseDiagnosedDistrictWiseAndDiseaseWiseData);
      return "Datset files for Disease Created Successfully";
   }
}