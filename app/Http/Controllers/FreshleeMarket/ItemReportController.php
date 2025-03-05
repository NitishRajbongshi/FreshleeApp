<?php

namespace App\Http\Controllers\FreshleeMarket;

use App\Http\Controllers\Controller;
use App\Models\MasterItemUnit;
use App\Models\Order\TblCustomerBookingDetail;
use Barryvdh\DomPDF\Facade\Pdf;
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
                ->leftJoin(
                    'smartag_market.master_item_units',
                    'smartag_market.tbl_customer_booking_details.qty_unit',
                    '=',
                    'smartag_market.master_item_units.unit_cd'
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
                    'item_price_in_unit', smartag_market.tbl_item_master.item_price_in,
                    'item_quantity', smartag_market.tbl_customer_booking_details.item_quantity,
                    'qty_unit', smartag_market.master_item_units.unit_cd,
                    'qty_unit_desc', smartag_market.master_item_units.unit_desc
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

    public function orderReport(Request $request)
    {
        try {
            $today = Carbon::today()->toDateString();
            $first = Carbon::now()->startOfMonth()->toDateString();
            $start = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
            $chartData = [];
            $chartLabels = [];
            $data = DB::table('smartag_market.tbl_customer_booking_details')
                ->select(
                    'smartag_market.tbl_customer_booking_details.item_cd',
                    'smartag_market.tbl_item_master.item_name',
                    'smartag_market.tbl_item_master.item_price_in',
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

            foreach ($data as $item) {
                $chartLabels[] = $item->item_name; // Labels for the pie chart
                $chartData[] = (float)$item->total_quantity; // Values for the pie chart
            }
            return view('admin.freshleeMarket.orderReport.orderReport', [
                'data' => $data,
                'start' => $start,
                'first' => $first,
                'today' => $today,
                'chartLabels' => $chartLabels,
                'chartData' => $chartData,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function reportHistory(Request $request)
    {
        try {
            $start = $request->start_date;
            $today = $request->end_date;
            $chartData = [];
            $chartLabels = [];
            $data = DB::table('smartag_market.tbl_customer_booking_details')
                ->select(
                    'smartag_market.tbl_customer_booking_details.item_cd',
                    'smartag_market.tbl_item_master.item_name',
                    'smartag_market.tbl_item_master.item_price_in',
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

            foreach ($data as $item) {
                $chartLabels[] = $item->item_name; // Labels for the pie chart
                $chartData[] = (float)$item->total_quantity; // Values for the pie chart
            }
            return view('admin.freshleeMarket.orderReport.reportHistory', [
                'data' => $data,
                'first' => $start,
                'today' => $today,
                'chartLabels' => $chartLabels,
                'chartData' => $chartData,
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
                    ->orderBy('date_created', 'desc')
                    ->value('actual_sale_price_per_1kg');
                // Check if the quantity is in grams and convert it to kilograms if needed
                $quantityInKg = ($item['qty_unit'] == 'gm')
                    ? $item['item_quantity'] / 1000
                    : $item['item_quantity'];
                // Calculate total price
                $totalPrice = $itemPrice * $quantityInKg;
                $priceList[] = array_merge($item, [
                    'price_per_kg' => $itemPrice,
                    'total_price' => $totalPrice,
                ]);
                Log::info($priceList);
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

    public function pricing(Request $request)
    {
        try {
            // Validate booking_id
            $booking_id = $request->booking_id;
            if (empty($booking_id)) {
                Log::info("Booking ID is missing!");
                throw new \Exception("Booking ID is required.");
            }

            // Fetch items for the given booking_ref_no
            $items = DB::table('smartag_market.tbl_customer_booking_details')
                ->select('item_cd', 'item_quantity', 'qty_unit', 'zone_cd')
                ->where('booking_ref_no', $booking_id)
                ->get();

            // Check if items are found
            if ($items->isEmpty()) {
                Log::info("No items found for the given booking reference number!");
                throw new \Exception("No items found for the given booking reference number.");
            }

            $itemData = [];
            $totalPrice = 0;

            // Process each item
            foreach ($items as $item) {
                $itemCode = $item->item_cd;
                $quantity = $item->item_quantity;
                $unit = strtolower($item->qty_unit); // Normalize to lowercase
                $zoneCd = $item->zone_cd;

                // Convert grams to kilograms or milliliters to liters
                if ($unit === 'gm' || $unit === 'ml') {
                    $quantity = $quantity / 1000;
                }

                $itemData[] = [
                    'item_cd' => $itemCode,
                    'quantity' => $quantity,
                    'zone_cd' => $zoneCd,
                ];
            }

            // Fetch prices for all items
            $itemCodes = collect($itemData)->pluck('item_cd')->toArray();
            $prices = DB::table('smartag_market.tbl_items_sale_price_for_customer_zone_wise')
                ->whereIn('item_cd', $itemCodes)
                ->pluck('actual_sale_price_per_1kg', 'item_cd'); // Get prices as key-value pair

            // Calculate total price
            foreach ($itemData as $data) {
                $itemCode = $data['item_cd'];
                $quantity = $data['quantity'];
                $pricePerKg = $prices[$itemCode] ?? 0; // Default to 0 if price not found

                if ($pricePerKg === 0) {
                    Log::warning("Price not found for item: $itemCode");
                }

                $totalPrice += $quantity * $pricePerKg;
            }

            // Return success response
            return response()->json([
                'status' => 'success',
                'booking_ref_no' => $booking_id,
                'total_price' => $totalPrice,
                'items' => $itemData,
            ], 201);
        } catch (\Exception $e) {
            // Log the error with context
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'booking_id' => $booking_id ?? null,
            ]);

            // Return error response
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(), // Provide error message for debugging
                'booking_ref_no' => $booking_id ?? null,
                'total_price' => 'NA',
                'items' => null,
            ], 500);
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

    public function modify(Request $request)
    {
        $user = Auth::user();
        $customer_id = $request->cust_id;
        $customer_name = $request->cust_name;
        $booking_id = $request->booking_id;
        $itemUnits = MasterItemUnit::all();
        $items = DB::table("smartag_market.tbl_item_master")
            ->select('item_cd', 'item_name', 'min_qty_to_order', 'unit_min_order_qty')
            ->orderBy('item_name', 'asc')->get();
        $user_orders = DB::table('smartag_market.tbl_customer_booking_details as booking')
            ->select(
                'booking.id',
                'booking.cust_id',
                'booking.booking_ref_no',
                'booking.item_cd',
                'item.item_name',
                'item.min_qty_to_order',
                'item.unit_min_order_qty',
                'booking.item_quantity',
                'booking.qty_unit',
                'unit.unit_desc'
            )
            ->leftJoin('smartag_market.tbl_item_master as item', 'booking.item_cd', '=', 'item.item_cd')
            ->leftJoin('smartag_market.master_item_units as unit', 'booking.qty_unit', '=', 'unit.unit_cd')
            ->where('booking.cust_id', $customer_id)
            ->where('booking.booking_ref_no', $booking_id)
            ->orderBy('item.item_name', 'asc')
            ->get();
        return view('admin.freshleeMarket.modifyOrder', [
            'user' => $user,
            'items' => $items,
            'customer_id' => $customer_id,
            'customer_name' => $customer_name,
            'booking_id' => $booking_id,
            'user_orders' => $user_orders,
            'itemUnits' => $itemUnits,
        ]);
    }

    public function update(Request $request)
    {
        Log::info('Request method: ' . $request->method());
        $item_id = $request->item_id;
        $item_cd = $request->item_cd;
        $booking_id = $request->booking_id;
        $item_quantity = $request->item_quantity;
        $item_unit = $request->item_unit;
        try {
            $data = DB::table('smartag_market.tbl_customer_booking_details')
                ->where('booking_ref_no', $booking_id)
                ->where('id', $item_id)
                ->update([
                    'item_quantity' => $item_quantity,
                    'qty_unit' => $item_unit,
                ]);
            return redirect()->back()->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_cd' => 'required',
            'item_quantity' => 'required',
            'qty_unit' => 'required',
        ]);
        try {
            $booking_id = $request->booking_id;
            $bookingDetails = DB::table("smartag_market.tbl_customer_booking_details")
                ->where("booking_ref_no", $booking_id)
                ->select('cust_id', 'order_date', 'zone_cd', 'is_delivered', 'delivery_address_cd', 'pin_code', 'status_cd', 'assigned_farmers_id', 'req_ack_no', 'is_harvested', 'delivered_by', 'delivered_at')
                ->get()->first();
            if ($bookingDetails) {
                $data = [
                    'cust_id' => $bookingDetails->cust_id,
                    'order_date' => $bookingDetails->order_date,
                    'booking_ref_no' => $booking_id,
                    'zone_cd' => $bookingDetails->zone_cd,
                    'is_delivered' => $bookingDetails->is_delivered,
                    'delivery_address_cd' => $bookingDetails->delivery_address_cd,
                    'pin_code' => $bookingDetails->pin_code,
                    'status_cd' => $bookingDetails->status_cd,
                    'item_cd' => $request->item_cd,
                    'item_quantity' => $request->item_quantity,
                    'assigned_farmers_id' => $bookingDetails->assigned_farmers_id,
                    'req_ack_no' => $bookingDetails->req_ack_no,
                    'is_harvested' => $bookingDetails->is_harvested,
                    'delivered_by' => $bookingDetails->delivered_by,
                    'delivered_at' => $bookingDetails->delivered_at,
                    'qty_unit' => $request->qty_unit,
                ];
                // insert a new item in the same booking id
                $insertStatus = DB::table("smartag_market.tbl_customer_booking_details")->insertGetId($data);
                if ($insertStatus) {
                    return redirect()->back()->with('success', 'New Item Added successfully.');
                } else {
                    return redirect()->back()->with('error', 'Failed to add item. Try again!');
                }
            } else {
                return redirect()->back()->with('error', 'Failed to add item. Try again!');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function destroy(Request $request)
    {
        try {
            if ($request->_token && $request->item_id) {
                $item = DB::table("smartag_market.tbl_customer_booking_details")
                    ->where('id', $request->item_id)
                    ->delete();
                if ($item) {
                    return redirect()->back()->with('success', 'Item deleted seccessfully!');
                } else {
                    return redirect()->back()->with('success', 'Item failed to delete!');
                }
            } else {
                return redirect()->back()->with('failed', 'Unauthorized access!');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function downloadWeeklyReport(Request $reuest)
    {
        $today = Carbon::today()->toDateString();;
        $start = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $first = Carbon::now()->startOfMonth()->toDateString();
        $itemCounts = DB::table('smartag_market.tbl_customer_booking_details')
            ->select(
                'smartag_market.tbl_customer_booking_details.item_cd',
                'smartag_market.tbl_item_master.item_name',
                'smartag_market.tbl_item_master.item_price_in',
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
        $data = [
            'header' => 'Weekly Report',
            'subheader' => 'Listing all the ordered items between ' . $start . ' to ' . $today,
            'date' => $today,
            'itemCounts' => $itemCounts
        ];
        $pdf = Pdf::loadView('admin.reports.weeklyItemReport', $data);
        $fileName = 'product_report_weekly.pdf';
        return $pdf->download($fileName);
    }
}
