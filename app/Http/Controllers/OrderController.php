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
        if ($address = Address::find($request->address_id)) {
            if ($address->user_id != $request->user_id) {
                return response()->json([
                    'data' => '用户不存在！'
                ]);
            }
            $carts = Cart::whereIn('id', $request->cart_id)
                ->where('user_id', $request->user_id)
                ->where('status', '10')->get();
            $arr = array();
            $quantity = [];
            $orders = [];
            foreach ($carts as $cart) {
                $code = '';
                for ($i = 0; $i < 32; $i++) {         //通过循环指定长度
                    $randcode = mt_rand(0, 9);     //指定为数字
                    $code .= $randcode;
                }
                $sku = Sku::where('id', $cart->id)->where('status', '10')->first();
                $arr[$sku->product_id][] = $sku;
                $quantity[$sku->product_id][] = $cart->quantity;
            }
            foreach ($arr as $key => $val) {
                $code = '';
                for ($i = 0; $i < 32; $i++) {         //通过循环指定长度
                    $randcode = mt_rand(0, 9);     //指定为数字
                    $code .= $randcode;
                }
                $product_fee = 0;
                $weight = 0;
                $express_fee = 0;
                foreach ($val as $k => $v) {
                    $skuInfo = Sku::where('status', '10')->find($v->id);
                    $cart_quantity = $quantity[$key][$k];
                    if (($skuInfo->quantity - $cart_quantity) >= 0) {
                        $product = Product::where('status', '10')->find($v->product_id);
                        $express = Express::where('status', '10')->find($product->express_id);
                        $product_fee += $cart_quantity * $skuInfo->price;
                        $weight += $cart_quantity * $skuInfo->weight;
                    }
                }
                if ($product_fee < $express->min_money) {
                    $express_fee = (ceil($weight / 1000)) * 6;
                }
                $order = Order::create([
                    'number' => $code,
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
                array_push($orders, $order);
                foreach ($val as $k => $v) {
                    $order_item = Order_item::create([
                        'order_id' => $order->id,
                        'product_id' => $v->product_id,
                        'product_full_name' => $product->name . $v->version,
                        'sku_id' => $v->id,
                        'quantity' => $quantity[$key][$k],
                        'price' => $v->price
                    ]);
                }
            }
            return response()->json([
                'data' => $orders
            ], 201);
        }
        return response()->json([
            'data' => '下单失败'
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
}
