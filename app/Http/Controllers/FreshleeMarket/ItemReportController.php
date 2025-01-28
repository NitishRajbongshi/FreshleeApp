<?php

namespace App\Http\Controllers\FreshleeMarket;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemReportController extends Controller
{
    public function __construct()
    {
        Log::info('ItemReportController Initialized');
    }

    public function index()
    {
        // Row query for futher use

        // SELECT
        // 	    smartag_market.tbl_customer_booking_details.cust_id,
        // 	    smartag_market.tbl_customer_booking_details.order_date,
        //      smartag_market.tbl_customer_booking_details.booking_ref_no, 
        // 	    smartag_market.tbl_customer_booking_details.order_date,
        // 	    smartag_market.tbl_customer_booking_details.is_delivered,
        // 	    smartag_market.tbl_user_login.full_name,
        //      smartag_market.tbl_user_login.phone_no,
        // 	    smartag_market.tbl_user_address.address_line1,
        //      JSON_AGG(
        //         JSON_BUILD_OBJECT(
        //             'item_cd', smartag_market.tbl_customer_booking_details.item_cd,
        //             'item_name', smartag_market.tbl_item_master.item_name,
        //             'item_unit', smartag_market.tbl_item_master.unit_min_order_qty,
        //             'item_quantity', smartag_market.tbl_customer_booking_details.item_quantity
        //         )
        //      ) AS order_items
        // FROM 
        //     smartag_market.tbl_customer_booking_details
        // LEFT JOIN
        //     smartag_market.tbl_user_login ON
        //     smartag_market.tbl_customer_booking_details.cust_id = smartag_market.tbl_user_login.user_id
        // LEFT JOIN
        //     smartag_market.tbl_user_address ON
        //     smartag_market.tbl_customer_booking_details.delivery_address_cd = smartag_market.tbl_user_address.address_cd
        // LEFT JOIN
        //     smartag_market.tbl_item_master ON
        //     smartag_market.tbl_customer_booking_details.item_cd = smartag_market.tbl_item_master.item_cd
        // WHERE smartag_market.tbl_customer_booking_details.booking_ref_no = '600239075420240229100856195266'
        // GROUP BY 
        // 	    smartag_market.tbl_customer_booking_details.cust_id,
        // 	    smartag_market.tbl_customer_booking_details.order_date,
        //      smartag_market.tbl_customer_booking_details.booking_ref_no,
        // 	    smartag_market.tbl_customer_booking_details.order_date,
        // 	    smartag_market.tbl_customer_booking_details.is_delivered,
        // 	    smartag_market.tbl_user_login.full_name,
        //      smartag_market.tbl_user_login.phone_no,
        // 	    smartag_market.tbl_user_address.address_line1;

        // SELECT 
        //     smartag_market.tbl_customer_booking_details.item_cd, 
        // 	   smartag_market.tbl_item_master.item_name,
        //     smartag_market.tbl_item_master.unit_min_order_qty,
        //     SUM(smartag_market.tbl_customer_booking_details.item_quantity) AS total_quantity
        // FROM 
        //     smartag_market.tbl_customer_booking_details
        // LEFT JOIN
        // 	   smartag_market.tbl_item_master
        // ON
        // 	   smartag_market.tbl_customer_booking_details.item_cd = smartag_market.tbl_item_master.item_cd
        // GROUP BY 
        //     smartag_market.tbl_customer_booking_details.item_cd,
        // 	   smartag_market.tbl_item_master.item_name,
        //     smartag_market.tbl_item_master.unit_min_order_qty;

        try {
            // get the neccessary dates
            $user = Auth::user();
            $today = Carbon::today()->toDateString();;
            $start = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
            $first = Carbon::now()->startOfMonth()->toDateString();
            $data = DB::table('smartag_market.tbl_customer_booking_details')
                ->leftJoin(
                    'smartag_market.tbl_user_login',
                    'smartag_market.tbl_customer_booking_details.cust_id',
                    '=',
                    'smartag_market.tbl_user_login.user_id'
                )
                ->leftJoin(
                    'smartag_market.tbl_user_address',
                    'smartag_market.tbl_customer_booking_details.delivery_address_cd',
                    '=',
                    'smartag_market.tbl_user_address.address_cd'
                )
                ->leftJoin(
                    'smartag_market.tbl_item_master',
                    'smartag_market.tbl_customer_booking_details.item_cd',
                    '=',
                    'smartag_market.tbl_item_master.item_cd'
                )
                ->select(
                    'smartag_market.tbl_customer_booking_details.cust_id',
                    DB::raw('DATE(smartag_market.tbl_customer_booking_details.order_date) as order_date'),
                    'smartag_market.tbl_customer_booking_details.booking_ref_no',
                    'smartag_market.tbl_customer_booking_details.is_delivered',
                    DB::raw('DATE(smartag_market.tbl_customer_booking_details.delivered_at) as delivered_at'),
                    'smartag_market.tbl_user_login.full_name',
                    'smartag_market.tbl_user_login.phone_no',
                    'smartag_market.tbl_user_address.address_line1',
                    DB::raw("JSON_AGG(
                JSON_BUILD_OBJECT(
                    'item_cd', smartag_market.tbl_customer_booking_details.item_cd,
                    'item_name', smartag_market.tbl_item_master.item_name,
                    'item_unit', smartag_market.tbl_item_master.unit_min_order_qty,
                    'item_quantity', smartag_market.tbl_customer_booking_details.item_quantity,
                    'qty_unit', smartag_market.tbl_customer_booking_details.qty_unit
                )
            ) AS order_items")
                )
                ->whereBetween(DB::raw('DATE(smartag_market.tbl_customer_booking_details.order_date)'), [$start, $today])
                ->groupBy(
                    'smartag_market.tbl_customer_booking_details.cust_id',
                    'smartag_market.tbl_customer_booking_details.order_date',
                    'smartag_market.tbl_customer_booking_details.booking_ref_no',
                    'smartag_market.tbl_customer_booking_details.is_delivered',
                    'smartag_market.tbl_customer_booking_details.delivered_at',
                    'smartag_market.tbl_user_login.full_name',
                    'smartag_market.tbl_user_login.phone_no',
                    'smartag_market.tbl_user_address.address_line1'
                )
                ->orderBy('smartag_market.tbl_customer_booking_details.order_date', 'desc')
                ->get();

            $itemCounts = DB::table('smartag_market.tbl_customer_booking_details')
                ->select(
                    'smartag_market.tbl_customer_booking_details.item_cd',
                    'smartag_market.tbl_item_master.item_name',
                    'smartag_market.tbl_item_master.item_price_in',
                    // DB::raw('SUM(smartag_market.tbl_customer_booking_details.item_quantity) AS total_quantity')
                    DB::raw("
                                SUM(
                                        CASE 
                                            WHEN 
                                                smartag_market.tbl_customer_booking_details.qty_unit = 'gm' then 
                                                    item_quantity/1000 
                                            ELSE 
                                                    item_quantity 
                                        END
                                    ) AS total_quantity
                            ")
                )
                ->leftJoin(
                    'smartag_market.tbl_item_master',
                    'smartag_market.tbl_customer_booking_details.item_cd',
                    '=',
                    'smartag_market.tbl_item_master.item_cd'
                )
                ->groupBy(
                    'smartag_market.tbl_customer_booking_details.item_cd',
                    'smartag_market.tbl_item_master.item_name',
                    'smartag_market.tbl_item_master.item_price_in'
                )
                ->whereBetween(DB::raw('DATE(smartag_market.tbl_customer_booking_details.order_date)'), [$start, $today])
                ->get();

            $picupAddress = DB::table("smartag_market.tbl_default_delivery_address")
                ->select('address_line1', 'address_line2', 'state_cd', 'district_cd', 'pin_code', 'latitude', 'longitude')
                ->get()
                ->first();

            return view('admin.freshleeMarket.userOrder', [
                'user' => $user,
                'data' => $data,
                'start' => $start,
                'first' => $first,
                'today' => $today,
                'picupAddress' => $picupAddress,
                'itemCounts' => $itemCounts
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function updateDeliveryStatus(Request $request)
    {
        try {
            $data = DB::table('smartag_market.tbl_customer_booking_details')
                ->where('booking_ref_no', $request->booking_ref_no)
                ->update([
                    'is_delivered' => $request->delivery_status,
                    'delivered_at' => $request->update_date,
                    'delivered_by' => Auth::user()->user_id,
                ]);
            return redirect()->back()->with('success', 'Delivery status updated successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function history(Request $request)
    {
        try {
            $start = $request->start_date;
            $today = $request->end_date;
            $data = DB::table('smartag_market.tbl_customer_booking_details')
                ->leftJoin(
                    'smartag_market.tbl_user_login',
                    'smartag_market.tbl_customer_booking_details.cust_id',
                    '=',
                    'smartag_market.tbl_user_login.user_id'
                )
                ->leftJoin(
                    'smartag_market.tbl_user_address',
                    'smartag_market.tbl_customer_booking_details.delivery_address_cd',
                    '=',
                    'smartag_market.tbl_user_address.address_cd'
                )
                ->leftJoin(
                    'smartag_market.tbl_item_master',
                    'smartag_market.tbl_customer_booking_details.item_cd',
                    '=',
                    'smartag_market.tbl_item_master.item_cd'
                )
                ->select(
                    'smartag_market.tbl_customer_booking_details.cust_id',
                    DB::raw('DATE(smartag_market.tbl_customer_booking_details.order_date) as order_date'),
                    'smartag_market.tbl_customer_booking_details.booking_ref_no',
                    'smartag_market.tbl_customer_booking_details.is_delivered',
                    'smartag_market.tbl_user_login.full_name',
                    'smartag_market.tbl_user_login.phone_no',
                    'smartag_market.tbl_user_address.address_line1',
                    DB::raw("JSON_AGG(
                    JSON_BUILD_OBJECT(
                        'item_cd', smartag_market.tbl_customer_booking_details.item_cd,
                        'item_name', smartag_market.tbl_item_master.item_name,
                        'item_unit', smartag_market.tbl_item_master.unit_min_order_qty,
                        'item_quantity', smartag_market.tbl_customer_booking_details.item_quantity
                    )
                ) AS order_items")
                )
                ->whereBetween(DB::raw('DATE(smartag_market.tbl_customer_booking_details.order_date)'), [$start, $today])
                ->groupBy(
                    'smartag_market.tbl_customer_booking_details.cust_id',
                    'smartag_market.tbl_customer_booking_details.order_date',
                    'smartag_market.tbl_customer_booking_details.booking_ref_no',
                    'smartag_market.tbl_customer_booking_details.is_delivered',
                    'smartag_market.tbl_user_login.full_name',
                    'smartag_market.tbl_user_login.phone_no',
                    'smartag_market.tbl_user_address.address_line1'
                )
                ->orderBy('smartag_market.tbl_customer_booking_details.order_date', 'desc')
                ->get();
            return view('admin.freshleeMarket.orderHistory', [
                'data' => $data,
                'first' => $start,
                'today' => $today
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function billing(Request $request)
    {
        try {
            $cust_id = $request->cust_id;
            $cust_name = $request->cust_name;
            $cust_phone = $request->cust_phone;
            $booking_id = $request->booking_id;
            $ordered_items = json_decode($request->order_items, true);
            $priceList = [];
            foreach ($ordered_items as $item) {
                // Fetch item price from the database
                $itemPrice = DB::table('smartag_market.tbl_items_sale_price_for_customer_zone_wise')
                    ->where('item_cd', $item['item_cd'])
                    ->value('actual_sale_price_per_1kg');
                // Check if the quantity is in grams and convert it to kilograms if needed
                $quantityInKg = ($item['qty_unit'] === 'gm')
                    ? $item['item_quantity'] / 1000
                    : $item['item_quantity'];
                // Calculate total price
                $totalPrice = $itemPrice * $quantityInKg;
                $priceList[] = array_merge($item, [
                    'price_per_kg' => $itemPrice,
                    'total_price' => $totalPrice,
                ]);
            }
            // dd($priceList);
            return view('admin.freshleeMarket.itemBill', [
                'cust_id' => $cust_id,
                'cust_name' => $cust_name,
                'cust_phone' => $cust_phone,
                'booking_id' => $booking_id,
                'priceList' => $priceList,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function markAsDelivered(Request $request)
    {
        Log::info($request->all());
        $bookingId = $request->input('booking_id');
        $itemCds = $request->input('item_cds');

        if (empty($itemCds)) {
            return response()->json(['message' => 'No items selected.'], 400);
        }

        foreach ($itemCds as $itemCd) {
            DB::table('smartag_market.tbl_customer_booking_details')
                ->where('item_cd', $itemCd)
                ->where('booking_ref_no', $bookingId)
                ->update(['is_delivered' => 'Y']);
        }

        return response()->json(['message' => 'Selected items marked as delivered successfully.']);
    }
}
