<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\MasterItemUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class InventoryController extends Controller
{
    public function __construct()
    {
        Log::info('Inventory Controller initiated');
    }
    public function index()
    {
        try {
            $inventory = Session::get('inventory', []);
            $items = DB::table('smartag_market.tbl_item_master')
                ->select('item_cd', 'item_name', 'item_price_in')
                ->get();
            $itemUnits = MasterItemUnit::all();
            return view('admin.storeInventory.index', [
                'items' => $items,
                'itemUnits' => $itemUnits,
                'inventory' => $inventory
            ]);
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
            $inventory = Session::get('inventory', []);
            $items = DB::table('smartag_market.tbl_item_master')
                ->select('item_cd', 'item_name', 'item_price_in')
                ->get();
            $itemUnits = MasterItemUnit::all();
            $inventory = Session::get('inventory', []);
            if ($request->has('item_cd')) {
                $inventory[] = [
                    'purchase_id' => 'fjksfjksjskjfks',
                    'item_cd' => $request->input('item_cd'),
                    'item_qty' => $request->input('item_qty'),
                    'item_unit' => $request->input('item_unit'),
                    'purchase_date' => $request->input('purchase_date'),
                    'item_price' => $request->input('item_price'),
                    'purchase_discount' => $request->input('purchase_discount'),
                    'total_price' => $request->input('total_price'),
                    'farmer_name' => $request->input('farmer_name')
                ];
                Session::put('inventory', $inventory);
                Log::info("Inventory List: ", Session::get('inventory'));
            }
            return view('admin.storeInventory.index', [
                'items' => $items,
                'itemUnits' => $itemUnits,
                'inventory' => $inventory
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }
}
