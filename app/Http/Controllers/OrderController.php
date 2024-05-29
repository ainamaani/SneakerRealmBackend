<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Account;
use App\Models\CustomUser;
use App\Models\Order;
use App\Models\Sneaker;
use App\Models\SneakerVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
                'sneaker_color' => 'required|string',
                'sneaker_size' => 'required|integer'

            ]);

            // extract attributes off the request object
            $user_id = $request->input('user_id');
            $delivery_address = $request->input('delivery_address');
            $payment_method = $request->input('payment_method');
            $sneaker_id = $request->input('sneaker_id');
            $quantity = $request->input('quantity');
            $sneaker_color = $request->input('sneaker_color');
            $sneaker_size = $request->input('sneaker_size');

            // get sneaker variant that is being purchased
            $sneaker_variant = SneakerVariant::where('sneaker_id', $sneaker_id)
                                ->where('color', $sneaker_color)
                                ->where('size', $sneaker_size)
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
                $discount_amount = $sneaker_price * (($sneaker_discount) / 100);
                $final_unit_price = $sneaker_price - $discount_amount ;
                $final_price = $final_unit_price * $quantity;

            }else{
                $final_price = $sneaker_price * $quantity;
            }

            // deduct the money off the account
            $account = Account::where('user_id', $user_id)->first();
            $current_balance = $account->account_balance;

            // check if the user has enough money on their account
            if($final_price > $current_balance){
                return response()->json(['error' => 'You do not have enough money on your account to complete this transaction'], 400);
            }

            // deduct money from account
            $account->account_balance -= $final_price;
            $account->save();

            // create the order
            $order = Order::create([
                'user_id' => $user_id,
                'sneaker_id' => $sneaker_id,
                'sneaker_variant_id' => $sneaker_variant->id,
                'order_number' => 'ORD-'. strtoupper(uniqid()),
                'status' => 'pending',
                'delivery_address' => $delivery_address,
                'payment_method' => $payment_method,
                'order_date' => now(),
                'unit_price' => $sneaker_price,
                'quantity_price' => $final_price,
                'quantity' => $quantity
            ]);

            // get all the user required to send email
            $user = CustomUser::find($user_id);

            Mail::to($user->email)->send(new OrderConfirmation($user, $order, $sneaker, $sneaker_variant));

            DB::commit();

            return response()->json(['message' => 'Order created successfully'], 201);
        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to create order: ' .$e->getMessage()], 500);
        }
    }

    public function fetchAllOrders(){
        try {
            //code...
            $orders = Order::all();
            // return the orders as a JSON object
            return response()->json($orders, 200);

        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to fetch orders: ' .$e->getMessage()], 500);
        }
    }

    public function fetchSingleUserOrders($id){
        try {
            //code...
            $user_orders = Order::where('user_id', $id )->get();
            // return the user orders
            return response()->json($user_orders, 200);

        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to fetch user orders: ' .$e->getMessage()], 500);
        }
    }
}
