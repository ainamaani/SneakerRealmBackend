<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sneaker;
use App\Models\SneakerVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function createOrder(Request $request){
        DB::beginTransaction();
        try {
            // validate request data
            $request->validate([
                'user_id' => 'required|exists:custom_users,id',
                'delivery_address' => 'required|string',
                'payment_method' => 'required|string',
                'sneaker_id' => 'required|exists:sneakers,id',
                'quantity' => 'required|integer|min:0',
                'sneaker_color' => 'required|string'

            ]);

            // extract attributes off the request object
            $user_id = $request->input('user_id');
            $delivery_address = $request->input('delivery_address');
            $payment_method = $request->input('payment_method');
            $sneaker_id = $request->input('sneaker_id');
            $quantity = $request->input('quantity');
            $sneaker_color = $request->input('sneaker_color');

            // get sneaker variant that is being purchased
            $sneaker_variant = SneakerVariant::where('sneaker_id', $sneaker_id)
                                ->where('color', $sneaker_color)
                                ->first();

            if(!$sneaker_variant){
                return response()->json(['error' => 'Failed to find the sneaker variant'], 404);
            }

            // check if requested quantity is available
            if($quantity > $sneaker_variant->quantity){
                return response()->json(['error' => 'The requested sneaker quantity is not available'], 400);
            }

            // deduct the quantity from the sneaker variant available stock
            $sneaker_variant->quantity -= $quantity;
            $sneaker_variant->save();
             
            // get sneaker that is being purchased
            $sneaker = Sneaker::find($sneaker_id);

            $sneaker_price = $sneaker->price;

            $sneaker_discount = $sneaker->discount;

            // initialise the final price variable
            $final_price = 0;

            if($sneaker_discount > 0){
                $final_price = $sneaker_price * $quantity * (($sneaker_discount) / 100) ;
            }else{
                $final_price = $sneaker_price * $quantity;
            }

            // create the order
            $order = Order::create([
                'user_id' => $user_id,
                'sneaker_id' => $sneaker_id,
                'order_number' => 'ORD-'. strtoupper(uniqid()),
                'status' => 'pending',
                'delivery_address' => $delivery_address,
                'payment_method' => $payment_method,
                'order_date' => now(),
                'unit_price' => $sneaker_price,
                'quantity_price' => $final_price,
                'quantity' => $quantity
            ]);

            DB::commit();

            return response()->json(['message' => 'Order created successfully'], 201);
        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to create order: ' .$e->getMessage()], 500);
        }
    }
}
