<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Account;
use App\Models\CustomUser;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sneaker;
use App\Models\SneakerVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate request data
            $request->validate([
                'user_id' => 'required|exists:custom_users,id',
                'delivery_address' => 'required|string',
                'payment_method' => 'required|string',
                'items' => 'required|array',
                'items.*.sneaker_id' => 'required|exists:sneakers,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.sneaker_color' => 'required|string',
                'items.*.sneaker_size' => 'required|integer'
            ]);
    
            // Extract attributes off the request object
            $user_id = $request->input('user_id');
            $delivery_address = $request->input('delivery_address');
            $payment_method = $request->input('payment_method');
            $items = $request->input('items');
    
            // Initialize the total price
            $total_price = 0;
    
            // Array to store order items
            $orderItems = [];
            $itemDetails = [];
    
            foreach ($items as $item) {
                $sneaker_variant = SneakerVariant::where('sneaker_id', $item['sneaker_id'])
                    ->where('color', $item['sneaker_color'])
                    ->where('size', $item['sneaker_size'])
                    ->first();
    
                if (!$sneaker_variant || $item['quantity'] > $sneaker_variant->quantity) {
                    DB::rollBack();
                    return response()->json(['error' => 'Invalid sneaker variant or quantity'], 400);
                }
    
                // Deduct the quantity from stock
                $sneaker_variant->quantity -= $item['quantity'];
                $sneaker_variant->save();
    
                $sneaker_price = $sneaker_variant->sneaker->price;
                $sneaker_discount = $sneaker_variant->sneaker->discount;
    
                // Calculate item price with discount
                if ($sneaker_discount > 0) {
                    $discounted_price = $sneaker_price * (1 - ($sneaker_discount / 100));
                } else {
                    $discounted_price = $sneaker_price;
                }
                $item_price = $discounted_price * $item['quantity'];
    
                // Add order item to the array
                $orderItems[] = [
                    'sneaker_variant_id' => $sneaker_variant->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $discounted_price,
                    'total_price' => $item_price,
                ];
    
                // Collect item details for email
                $itemDetails[] = [
                    'sneaker' => $sneaker_variant->sneaker,
                    'sneaker_variant' => $sneaker_variant,
                    'quantity' => $item['quantity'],
                    'unit_price' => $discounted_price,
                    'total_price' => $item_price
                ];
    
                $total_price += $item_price;
            }
    
            // Create the order now with total price
            $order = Order::create([
                'user_id' => $user_id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'delivery_address' => $delivery_address,
                'payment_method' => $payment_method,
                'status' => 'pending',
                'order_date' => now(),
                'total_price' => $total_price
            ]);
    
            // Create order items
            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }
    
            // Deduct the money from the account
            $account = Account::where('user_id', $user_id)->first();
            $current_balance = $account->account_balance;
    
            // Check if the user has enough money in their account
            if ($total_price > $current_balance) {
                DB::rollBack();
                return response()->json(['error' => 'You do not have enough money in your account to complete this transaction'], 400);
            }
    
            // Deduct money from account
            $account->account_balance -= $total_price;
            $account->save();
    
            // Get all the user data required to send email
            $user = CustomUser::find($user_id);
    
            // Send order confirmation email
            Mail::to($user->email)->send(new OrderConfirmation($user, $order, $itemDetails));
    
            DB::commit();
    
            return response()->json(['message' => 'Order created successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create order: ' . $e->getMessage()], 500);
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
