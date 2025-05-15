<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
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
