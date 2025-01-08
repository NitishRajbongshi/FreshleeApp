<?php

namespace App\Http\Controllers\FarmerInventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FarmerStockController extends Controller
{
    public function __construct()
    {
        Log::info('ItemReportController Initialized');
    }

    public function index() {
        try {
            return view('admin.farmerInventory.index');
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function create()
    {
        try {
            return view('admin.farmerInventory.create');
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
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'item_name' => 'required|string|max:255',
                'quantity' => 'required|integer',
                'price' => 'required|numeric',
            ]);

            // Create a new stock item
            $stockItem = new StockItem();
            $stockItem->item_name = $validatedData['item_name'];
            $stockItem->quantity = $validatedData['quantity'];
            $stockItem->price = $validatedData['price'];
            $stockItem->save();

            // Redirect to the index page with success message
            return redirect()->route('farmerInventory.index')->with('success', 'Stock item added successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->withErrors('An error occurred while adding the stock item.');
        }
    }
}
