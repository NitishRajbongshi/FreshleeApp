<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\MasterItemUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    public function __construct()
    {
        Log::info('Inventory Controller initiated');
    }
    public function index()
    {
        try {
            $user = Auth::user();
            $items = DB::table('smartag_market.tbl_item_master')
                ->select('item_cd', 'item_name', 'item_price_in')
                ->get();
            $itemUnits = MasterItemUnit::all();
            return view('admin.storeInventory.index', [
                'user' => $user,
                'items' => $items,
                'itemUnits' => $itemUnits
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('errors.generic');
        }
    }

    public function store(Request $request) {
        dd($request->all());
    }
}
