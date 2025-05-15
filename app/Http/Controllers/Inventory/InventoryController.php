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
            $expenditure = Session::get('expenditure', []);
            $items = DB::table('smartag_market.tbl_item_master')
                ->select('item_cd', 'item_name', 'item_price_in')
                ->get();
            $expenditureList = DB::table('smartag_market.master_expenditure_types')
                ->select('exp_cd', 'exp_desc')
                ->get();
            $itemUnits = MasterItemUnit::all();
            return view('admin.storeInventory.index', [
                'items' => $items,
                'expenditureList' => $expenditureList,
                'itemUnits' => $itemUnits,
                'inventory' => $inventory,
                'expenditure' => $expenditure
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function itemCart(Request $request)
    {
        try {
            $items = DB::table('smartag_market.tbl_item_master')
                ->select('item_cd', 'item_name', 'item_price_in')
                ->get();
            $expenditureList = DB::table('smartag_market.master_expenditure_types')
                ->select('exp_cd', 'exp_desc')
                ->get();
            $itemUnits = MasterItemUnit::all();
            $inventory = Session::get('inventory', []);
            $expenditure = Session::get('expenditure', []);
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
                'expenditureList' => $expenditureList,
                'itemUnits' => $itemUnits,
                'inventory' => $inventory,
                'expenditure' => $expenditure
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function expdCart(Request $request)
    {
        try {
            $items = DB::table('smartag_market.tbl_item_master')
                ->select('item_cd', 'item_name', 'item_price_in')
                ->get();
            $expenditureList = DB::table('smartag_market.master_expenditure_types')
                ->select('exp_cd', 'exp_desc')
                ->get();
            $itemUnits = MasterItemUnit::all();
            $inventory = Session::get('inventory', []);
            $expenditure = Session::get('expenditure', []);
            if ($request->has('exp_cd')) {
                $expenditure[] = [
                    'purchase_id' => 'fjksfjksjskjfks',
                    'exp_cd' => $request->input('exp_cd'),
                    'exp_desc' => "Food",
                    'expense' => $request->input('expense'),
                ];
                Log::info("Expenditure: ", $expenditure);
                Session::put('expenditure', $expenditure);
                Log::info("Expenditure List: ", Session::get('expenditure'));
            }
            return view('admin.storeInventory.index', [
                'items' => $items,
                'expenditureList' => $expenditureList,
                'itemUnits' => $itemUnits,
                'inventory' => $inventory,
                'expenditure' => $expenditure
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
