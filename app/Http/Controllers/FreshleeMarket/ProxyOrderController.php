<?php

namespace App\Http\Controllers\FreshleeMarket;

use App\Http\Controllers\Controller;
use App\Models\MasterItemUnit;
use App\Services\ProxyOrderService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProxyOrderController extends Controller
{
    protected $userService;
    protected $proxyOrderService;

    public function __construct(UserService $userService, ProxyOrderService $proxyOrderService)
    {
        $this->userService = $userService;
        $this->proxyOrderService = $proxyOrderService;
    }

    public function index()
    {
        $user = Auth::user();
        // Clear previous cart
        Session::forget('cart');
        Session::forget('UserCart');
        $customers = DB::table("smartag_market.tbl_user_login")
            ->select("user_id", "full_name", "phone_no")
            ->orderBy("full_name", "asc")
            ->get();
        return view('admin.proxyOrder.searchUser', [
            'user' => $user,
            'customers' => $customers
        ]);
    }

    public function getCustomerAddress(Request $request)
    {
        $uid = $request->input('uid');
        $response = $this->userService->getUserAddress($uid);
        return response()->json($response);
    }

    public function create(Request $request)
    {
        Log::info($request->all());
        $user = Auth::user();
        $itemUnits = MasterItemUnit::all();
        $customerName = $request->input('name');
        $customerPhone = $request->input('phone');
        $customerPin = $request->input('pin');
        $customerAddress = $request->input('address');
        $message = '';
        $response = $this->proxyOrderService->getPriceListZoneWiseCustomer($customerPin);
        if ($response['status']) {
            $message = 'Item List fetched successfully';
            $items = $response['sale_price_data'];
        } else {
            $message = 'Failed to fetched Item List';
            $items = [];
        }

        // if route has item cd 
        $cart = Session::get('cart', []);
        $userCart = Session::get('UserCart', []);
        if ($request->has('item_cd')) {
            $item_cd = $request->input('item_cd');
            $item_name = $request->input('item_name');
            $item_min_order = $request->input('item_min_order');
            $item_min_qty = $request->input('item_min_qty');
            $item_min_unit = $request->input('item_min_unit');
            $item_qty = $request->input('qty');
            $item_unit = $request->input('qty_unit');

            $userCart[] = [
                'item_cd' => $item_cd,
                'item_name' => $item_name,
                'item_min_order' => $item_min_order,
                'item_qty' => $item_qty,
                'item_unit' => $item_unit
            ];

            // convert qty to API supported units 
            if(($item_min_unit == 'kg') || ($item_min_unit == 'ltr')) {
                $item_min_qty = $item_min_qty * 1000;
            }
            if(($item_unit == 'kg') || ($item_unit == 'ltr')) {
                $item_qty = $item_qty * 1000;
            }
            $unit = $item_qty / $item_min_qty;
            Log::info("unit: " . $unit);

            $cart[] = [
                'item_cd' => $item_cd,
                'qty' => $unit
            ];
           
            Session::put('cart', $cart);
            Session::put('UserCart', $userCart);
        }
        Log::info('API Cart:', ['cart' => $cart]);
        Log::info('User Cart:', ['cart' => $userCart]);
        return view('admin.proxyOrder.userCart', [
            'user' => $user,
            'itemUnits' => $itemUnits,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'customer_pin' => $customerPin,
            'customer_address' => $customerAddress,
            'message' => $message,
            'items' => $items,
            'order_items' => $userCart
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();
        $customerName = $request->input('name');
        $customerPhone = $request->input('phone');
        $order = [
            'phone_no' => $request->input('phone'),
            'pin_code' => $request->input('pin'),
            'delivery_address_cd' => $request->input('address'),
            'order_dtls' => Session::get('cart', [])
        ];
        $apiUrl = "http://43.205.45.246:8082/agriMarket/bookCustomersOrder";
        // Send POST request
        $response = Http::withHeaders([
            'x-access-token' => Session::get('accessToken')
        ])->post($apiUrl, $order);
        if ($response->successful()) {
            $responseData = $response->json();
            if ($responseData['status']) {
                $orderedItems = Session::get('UserCart', []);
                Session::forget('cart');
                Session::forget('UserCart');
                return view('admin.proxyOrder.confirmation', [
                    'user' => $user,
                    'customer_name' => $customerName,
                    'customer_phone' => $customerPhone,
                    'order_status' => 'SUCCESS',
                    'message' => $responseData['msg'],
                    'booking_id' => $responseData['booking_ref_no'],
                    'orderedItems' => $orderedItems,
                ]);
            } else {
                return redirect()->route('admin.proxy.user.list')->with('error', 'Failed to place order');
            }
        } else {
            return redirect()->route('admin.proxy.user.list')->with('error', 'Failed to place order');
        }
    }
}
