<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\ProxyOrderService;

class InvoiceController extends Controller
{

    protected $proxyOrderService;
    public function __construct(ProxyOrderService $proxyOrderService)
    {
        Log::info('ItemReportController Initialized');
        $this->proxyOrderService = $proxyOrderService;
    }
    public function generateInvoice(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $customerName = $request->input('customer_name');
        $customerPhone = $request->input('customer_phone');
        $selectedItems = $request->input('items', []);
        Log::info($selectedItems);
        $deliveryCharge = $request->input('hdnDeliveryCharge');
        $bagCharge = $request->input('hdnBagCharge');
        $totalAmount = $request->input('total_amount');
        $amountInWords = $this->numberToWords($totalAmount);
        // get logo from public folder
        $logo = public_path('admin_assets/img/favicon/banner.png');
        $payment_qr_code = public_path('admin_assets/img/Freshlee_Payment_QR_Code.png');
        $data = [
            'booking_id' => $bookingId,
            'date' => date('Y-m-d'),
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'items' => $selectedItems,
            'deliveryCharge' => $deliveryCharge,
            'bagCharge' => $bagCharge,
            'total_amount' => $totalAmount,
            'amountInWords' => $amountInWords,
            'logo' => $logo,
            'qr_code' => $payment_qr_code
        ];

        $custName = str_replace(' ', '_', strtolower($customerName));
        $pdf = Pdf::loadView('admin.freshleeMarket.invoice', $data);
        $fileName = 'Invoice_' . $custName . '.pdf';
        return $pdf->download($fileName);
    }


    public function generateHistoryInvoice(Request $request)
    {
        $bookingId = $request->input('booking-id');
        $customerName = $request->input('cust-name');
        $customerPhone = $request->input('ph-no');
        $customerAddress = $request->input('cust-addr');
        $zone_cd = $request->input('zcd');

        $data = DB::table('smartag_market.tbl_customer_booking_details')
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
                DB::raw("JSON_AGG(
                    JSON_BUILD_OBJECT(
                        'item_cd', smartag_market.tbl_customer_booking_details.item_cd,
                        'item_name', smartag_market.tbl_item_master.item_name,
                        'item_unit', smartag_market.tbl_item_master.unit_min_order_qty,
                        'item_quantity', smartag_market.tbl_customer_booking_details.item_quantity,
                        'price' , smartag_market.tbl_customer_booking_details.price,
                        'item_price_rate' , smartag_market.tbl_customer_booking_details.item_price_rate
                    )
                ) AS order_items")
            )
            ->where('smartag_market.tbl_customer_booking_details.booking_ref_no', $bookingId)
            ->groupBy(
                'smartag_market.tbl_customer_booking_details.cust_id',
                'smartag_market.tbl_customer_booking_details.order_date',
                'smartag_market.tbl_customer_booking_details.booking_ref_no',
                'smartag_market.tbl_customer_booking_details.is_delivered'
            )
            ->orderBy('smartag_market.tbl_customer_booking_details.order_date', 'desc')
            ->get()->first();
        $order_date = $data->order_date;
        $order_items = $data->order_items;
        $json = json_encode($order_items);
        Log::info($json);
        $orderItems = json_decode($order_items, true);

        $totalPrice = 0;
        $selectedItems = [];



        foreach ($orderItems as $item) {

            $jsn = [
                "name" => $item['item_name'],
                "quantity" => $item['item_quantity'],
                "qty_unit" => $item['item_unit'],
                "price_per_kg" => $item["item_price_rate"],
                "total_price" => $item['price'],
                "price_unit" => $item['item_unit']
            ];
            $jsnStr = json_encode($jsn);
            array_push($selectedItems, $jsn);
            $totalPrice += $item['price'];
        }

        Log::info(($totalPrice));

        $deliveryCharge = 0;
        $bagCharge = 0;
        $totalAmount = $totalPrice;
        $amountInWords = $this->numberToWords($totalAmount);
        // get logo from public folder
        $logo = public_path('admin_assets/img/favicon/banner.png');
        $payment_qr_code = public_path('admin_assets/img/Freshlee_Payment_QR_Code.png');
        $data = [
            'booking_id' => $bookingId,
            'date' => $order_date,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'items' => $selectedItems,
            'deliveryCharge' => $deliveryCharge,
            'bagCharge' => $bagCharge,
            'total_amount' => $totalAmount,
            'amountInWords' => $amountInWords,
            'logo' => $logo,
            'qr_code' => $payment_qr_code
        ];
        Log::info($data);
        $custName = str_replace(' ', '_', strtolower($customerName));
        $pdf = Pdf::loadView('admin.freshleeMarket.invoice', $data);
        $fileName = 'Invoice_' . $custName . '.pdf';
        return $pdf->download($fileName);
        // return true;
    }

    public function numberToWords($number)
    {
        $units = ["", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine"];
        $teens = ["ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen"];
        $tens = ["", "ten", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety"];

        if ($number == 0) {
            return "zero";
        }

        $words = "";

        if ($number < 0) {
            $words = "minus ";
            $number = abs($number);
        }

        if ($number < 10) {
            $words .= $units[intval($number)];
        } elseif ($number < 20) {
            $words .= $teens[$number - 10];
        } elseif ($number < 100) {
            $words .= $tens[intval($number / 10)] . " " . $units[$number % 10];
        } elseif ($number < 1000) {
            $words .= $units[intval($number / 100)] . " hundred " . $this->numberToWords($number % 100);
        } elseif ($number < 1000000) {
            $words .= $this->numberToWords(intval($number / 1000)) . " thousand " . $this->numberToWords($number % 1000);
        } else {
            $words .= $this->numberToWords(intval($number / 1000000)) . " million " . $this->numberToWords($number % 1000000);
        }

        return trim($words);
    }
}
