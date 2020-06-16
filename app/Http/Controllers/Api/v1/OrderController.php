<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResourceCollection;
use App\Http\Resources\OrderResource;

use App\Order;

class OrderController extends Controller
{
    public function index():OrderResourceCollection
    {
    	return new OrderResourceCollection(Order::paginate());
    }
    public function show(Order $order):OrderResource
    {
    	return new OrderResource($order);
    }
    public function store(Request $request):OrderResource
    {
    	$request->validate([
    		'cart'		=>'required',
    		'address'	=>'required',
    		'name'		=>'required',
    		'paymentId'	=>'required'
    	]);

    	$order = Order::create($request->all());
    	return new OrderResource($order);
    }
    public function update(Order $order, Request $request):OrderResource
    {
    	$order->update($request->all());
    	return new OrderResource($order);
    }
    public function destroy(Order $order)
    {
    	$order->delete();
    	return response()->json([]);
    }
}













