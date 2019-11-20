<?php

namespace App\Http\Controllers;

use App\Address;
use App\Cart;
use App\Express;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\CartCollection;
use App\Http\Resources\CartResource;
use App\Order;
use App\Order_item;
use App\Product;
use App\Sku;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Integer;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * 创建订单
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
//        Log::info('hahaha');
        if ($address = Address::find($request->address_id)) {
            if ($address->user_id != $request->user_id) {
                return response()->json([
                    'data' => '地址中的用户信息与传递的用户信息不匹配'
                ]);
            }
            DB::beginTransaction();
            try {
                $order_items = [];
                $product_fee = 0;
                $weight = 0;
                $carts = Cart::whereIn('id', $request->cart_id)
                    ->where('status', '10')->get();
                foreach ($carts as $cart) {
                    if ($cart->user_id != $request->user_id) {
                        return response()->json([
                            'data' => '购物车中的用户信息与传递的用户信息不匹配'
                        ]);
                    }
                    $sku = Sku::where('id', $cart->sku_id)->where('status', '10')->first();
                    $product = Product::where('status', '10')->find($sku->product_id);
                    $express = Express::where('status', '10')->find($product->express_id);
                    if (($sku->quantity - $cart->quantity >= 0)) {
                        $product_fee += $sku->price * $cart->quantity;
                        $weight += $sku->weight * $cart->quantity;
                    }
                    $order_item = [
                        'product_id' => $product->id,
                        'product_full_name' => $product->name . $sku->version,
                        'sku_id' => $sku->id,
                        'quantity' => $cart->quantity,
                        'price' => $sku->price
                    ];
                    array_push($order_items, $order_item);
                }
                if ($product_fee < $express->min_money) {
                    $express_fee = (ceil($weight / $express->weight)) * $express->fee;
                } else {
                    $express_fee = 0;
                }
                $number = Str::random(32);
                $order = Order::create([
                    'number' => $number,
                    'user_id' => $request->user_id,
                    'product_fee' => $product_fee,
                    'express_fee' => $express_fee,
                    'total_fee' => $product_fee + $express_fee,
                    'receiver_name' => $address->name,
                    'receiver_province' => $address->province,
                    'receiver_city' => $address->city,
                    'receiver_district' => $address->district,
                    'receiver_detail' => $address->detail,
                    'receiver_mobile' => $address->mobile,
                ]);
                foreach ($order_items as $order_item) {
                    $order_item ['order_id'] = $order->id;
                    $order_item = Order_item::create($order_item, true);
                }
                DB::commit();
                return response()->json([
                    'data' => $order
                ], 201);
            } catch (\Exception $e) {
                //接收异常处理并回滚
                DB::rollBack();
                return [
                    'status' => $e->getCode(),
                    'msg' => $e->getMessage()
                ];
            }
        }
        return response()->json([
            'data' => '地址不存在'
        ], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
     * 计算商品运费
     *
     * @param 购物车对象集合
     * @return
     */
    public function express_fee($carts)
    {
        $product_fee = 0;
        $weight = 0;
        $express_fee = 0;
        return $carts;
    }
}
