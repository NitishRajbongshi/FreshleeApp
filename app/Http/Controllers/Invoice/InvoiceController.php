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
        $selectedItems = $request->input('items', []);
        Log::info($selectedItems);
        $totalAmount = $request->input('total_amount');
        $amountInWords = $this->numberToWords($totalAmount);
        $data = [
            'booking_id' => $bookingId,
            'date' => date('Y-m-d'),
            'customer_name' => $customerName,
            'items' => $selectedItems,
            'total_amount' => $totalAmount,
            'amountInWords' => $amountInWords
        ];

        $pdf = Pdf::loadView('invoice', $data);

        return $pdf->download("Invoice.pdf");
    }

    public function numberToWords($number) {
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
            $words .= $units[$number];
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
