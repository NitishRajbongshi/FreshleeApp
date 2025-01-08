<?php

namespace App\Http\Controllers\MIS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\FixedMasters\District;
use App\Models\Appmaster\CropDisease;
use Carbon\Carbon;

class ReportController extends Controller
{
    //
    public function index()
    {
        $initialPage = true;
        $distMaster = District::orderBy('district_name')->get();
        $digeaseMaster = CropDisease::orderBy('disease_name')->get();
        $todays_date = Carbon::now();
        $date_last_30_days = Carbon::now()->subDays(30);
        // $listDiagnosedDisease = DB::table('ag_disease_diagnose_dtls AS diag_dtls')
        //     ->select(
        //         'diag_dtls.disease_diagnosed_cd',
        //         'diag_dtls.requested_by',
        //         'diag_dtls.requested_on',
        //         'diag_dtls.disease_cd',
        //         'disg_m.disease_name',
        //         'lat',
        //         'lon',
        //         'diag_dtls.district_cd',
        //         'dist_m.district_name',
        //         'users.phone',
        //         'diag_dtls.locality_name'
        //     )
        //     ->join('ag_crop_disease_master AS disg_m', 'disg_m.disease_cd', '=', 'diag_dtls.disease_cd')
        //     ->join('tbl_district_master AS dist_m', 'dist_m.district_cd', '=', 'diag_dtls.district_cd')
        //     ->leftJoin('users', 'users.user_id', '=', 'diag_dtls.requested_by')
        //     ->where('diag_dtls.disease_cd', '!=', '35')
        //     ->orderBy('requested_on', 'desc')->get();

        return view(
            'admin.mis.misHomePage',
            compact(
                'initialPage',
                'distMaster',
                'digeaseMaster',
                'todays_date',
                'date_last_30_days'
            )
        );
    }

    public function getMISReport(Request $request)
    {
        try {
            DB::enableQueryLog();
            $initialPage = false;
            $distMaster = District::orderBy('district_name')->get();
            $digeaseMaster = CropDisease::orderBy('disease_name')->get();
            $todays_date = Carbon::now();
            $date_last_30_days = Carbon::now()->subDays(30);

            $user_select_from_date = $request->frm_date;
            $user_select_to_date = $request->to_date;
            $user_selected_district_cd = $request->sel_dist_cd;
            $user_selected_disease_cd = $request->sel_disease_cd;

            $listDiagnosedDisease = null;
            if ($user_selected_district_cd == 'A' && $user_selected_disease_cd == 'A') {
                $listDiagnosedDisease = DB::table('ag_disease_diagnose_dtls AS diag_dtls')
                    ->select(
                        'diag_dtls.disease_diagnosed_cd',
                        'diag_dtls.requested_by',
                        'diag_dtls.requested_on',
                        'diag_dtls.disease_cd',
                        'disg_m.disease_name',
                        'lat',
                        'lon',
                        'diag_dtls.district_cd',
                        'dist_m.district_name',
                        'users.phone',
                        'diag_dtls.locality_name'
                    )
                    ->join('ag_crop_disease_master AS disg_m', 'disg_m.disease_cd', '=', 'diag_dtls.disease_cd')
                    ->join('tbl_district_master AS dist_m', 'dist_m.district_cd', '=', 'diag_dtls.district_cd')
                    ->leftJoin('users', 'users.user_id', '=', 'diag_dtls.requested_by')
                    ->where('diag_dtls.disease_cd', '!=', '35')
                    // ->where('diag_dtls.district_cd', '=', $user_selected_district_cd)
                    // ->where('diag_dtls.disease_cd', '=', $user_selected_disease_cd)
                    ->whereBetween('diag_dtls.requested_on', [$user_select_from_date, $user_select_to_date])
                    ->orderBy('requested_on', 'desc')->get();
                $query = DB::getQueryLog();
                Log::info(end($query));
                return view(
                    'admin.mis.misHomePage',
                    compact(
                        'listDiagnosedDisease',
                        'initialPage',
                        'distMaster',
                        'digeaseMaster',
                        'todays_date',
                        'date_last_30_days',
                        'user_select_from_date',
                        'user_select_to_date'
                    )
                );
            }
            if ($user_selected_district_cd == 'A' && $user_selected_disease_cd != 'A') {
                $listDiagnosedDisease = DB::table('ag_disease_diagnose_dtls AS diag_dtls')
                    ->select(
                        'diag_dtls.disease_diagnosed_cd',
                        'diag_dtls.requested_by',
                        'diag_dtls.requested_on',
                        'diag_dtls.disease_cd',
                        'disg_m.disease_name',
                        'lat',
                        'lon',
                        'diag_dtls.district_cd',
                        'dist_m.district_name',
                        'users.phone',
                        'diag_dtls.locality_name'
                    )
                    ->join('ag_crop_disease_master AS disg_m', 'disg_m.disease_cd', '=', 'diag_dtls.disease_cd')
                    ->join('tbl_district_master AS dist_m', 'dist_m.district_cd', '=', 'diag_dtls.district_cd')
                    ->leftJoin('users', 'users.user_id', '=', 'diag_dtls.requested_by')
                    ->where('diag_dtls.disease_cd', '!=', '35')
                    // ->where('diag_dtls.district_cd', '=', $user_selected_district_cd)
                    ->where('diag_dtls.disease_cd', '=', $user_selected_disease_cd)
                    ->whereBetween('diag_dtls.requested_on', [$user_select_from_date, $user_select_to_date])
                    ->orderBy('requested_on', 'desc')->get();

                $query = DB::getQueryLog();
                Log::info(end($query));
                return view(
                    'admin.mis.misHomePage',
                    compact(
                        'listDiagnosedDisease',
                        'initialPage',
                        'distMaster',
                        'digeaseMaster',
                        'todays_date',
                        'date_last_30_days',
                        'user_select_from_date',
                        'user_select_to_date'
                    )
                );
            }

            if ($user_selected_district_cd != 'A' && $user_selected_disease_cd == 'A') {
                $listDiagnosedDisease = DB::table('ag_disease_diagnose_dtls AS diag_dtls')
                    ->select(
                        'diag_dtls.disease_diagnosed_cd',
                        'diag_dtls.requested_by',
                        'diag_dtls.requested_on',
                        'diag_dtls.disease_cd',
                        'disg_m.disease_name',
                        'lat',
                        'lon',
                        'diag_dtls.district_cd',
                        'dist_m.district_name',
                        'users.phone',
                        'diag_dtls.locality_name'
                    )
                    ->join('ag_crop_disease_master AS disg_m', 'disg_m.disease_cd', '=', 'diag_dtls.disease_cd')
                    ->join('tbl_district_master AS dist_m', 'dist_m.district_cd', '=', 'diag_dtls.district_cd')
                    ->leftJoin('users', 'users.user_id', '=', 'diag_dtls.requested_by')
                    ->where('diag_dtls.disease_cd', '!=', '35')
                    ->where('diag_dtls.district_cd', '=', $user_selected_district_cd)
                    // ->where('diag_dtls.disease_cd', '=', $user_selected_disease_cd)
                    ->whereBetween('diag_dtls.requested_on', [$user_select_from_date, $user_select_to_date])
                    ->orderBy('requested_on', 'desc')->get();
                $query = DB::getQueryLog();
                Log::info(end($query));
                return view(
                    'admin.mis.misHomePage',
                    compact(
                        'listDiagnosedDisease',
                        'initialPage',
                        'distMaster',
                        'digeaseMaster',
                        'todays_date',
                        'date_last_30_days',
                        'user_select_from_date',
                        'user_select_to_date'
                    )
                );
            }
            if ($user_selected_district_cd != 'A' && $user_selected_disease_cd != 'A') {
                $listDiagnosedDisease = DB::table('ag_disease_diagnose_dtls AS diag_dtls')
                    ->select(
                        'diag_dtls.disease_diagnosed_cd',
                        'diag_dtls.requested_by',
                        'diag_dtls.requested_on',
                        'diag_dtls.disease_cd',
                        'disg_m.disease_name',
                        'lat',
                        'lon',
                        'diag_dtls.district_cd',
                        'dist_m.district_name',
                        'users.phone',
                        'diag_dtls.locality_name'
                    )
                    ->join('ag_crop_disease_master AS disg_m', 'disg_m.disease_cd', '=', 'diag_dtls.disease_cd')
                    ->join('tbl_district_master AS dist_m', 'dist_m.district_cd', '=', 'diag_dtls.district_cd')
                    ->leftJoin('users', 'users.user_id', '=', 'diag_dtls.requested_by')
                    ->where('diag_dtls.disease_cd', '!=', '35')
                    ->where('diag_dtls.district_cd', '=', $user_selected_district_cd)
                    ->where('diag_dtls.disease_cd', '=', $user_selected_disease_cd)
                    ->whereBetween('diag_dtls.requested_on', [$user_select_from_date, $user_select_to_date])
                    ->orderBy('requested_on', 'desc')->get();
                $query = DB::getQueryLog();
                Log::info(end($query));
                return view(
                    'admin.mis.misHomePage',
                    compact(
                        'listDiagnosedDisease',
                        'initialPage',
                        'distMaster',
                        'digeaseMaster',
                        'todays_date',
                        'date_last_30_days',
                        'user_select_from_date',
                        'user_select_to_date'
                    )
                );
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }


    }
}
