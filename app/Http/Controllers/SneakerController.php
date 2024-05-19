<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Sneaker;

class SneakerController extends Controller
{
    public function store(Request $request)
{
    DB::beginTransaction();

    try {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'brand' => 'required|string',
            'discount' => 'required|numeric',
            'variants' => 'required|array',
            'variants.*.size' => 'required|integer',
            'variants.*.color' => 'required|string',
            'variants.*.quantity' => 'required|integer'
        ]);

        $sneaker = Sneaker::create($request->only(['name', 'description', 'price', 'brand', 'discount']));

        foreach ($request->input('variants') as $variant) {
            $sneaker->variants()->create($variant);
        }

        DB::commit();

        return response()->json(['message' => 'Sneaker created successfully'], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Failed to create sneaker: ' . $e->getMessage()], 500);
    }
}


    public function index(){
        try {
            // retrieve all sneakers
            $sneakers = Sneaker::with('variants')->get();

            // return a success response
            return response()->json($sneakers, 200);

        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to fetch all sneakers: ' .$e->getMessage()], 500);
        }
    }

    public function show($id){
        try {
            // retrieve object with the ID
            $sneaker = Sneaker::with('variants')->findOrFail($id);
            if(!$sneaker){
                return response()->json(['error' => 'No sneaker has that ID'], 404);
            }

            return response()->json($sneaker, 200);
        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to fetch the sneakers with that ID' .$e->getMessage()]);
        }
    }

    public function destroy($id){
        try {
            // retrieve the sneaker to be deleted
            $sneaker = Sneaker::find($id);

            // check if the exists
            if(!$sneaker){
                return response()->json(['error' => 'No sneaker has that ID'], 404);
            }

            // delete the sneaker
            $sneaker->delete();

            // return success response
            return response()->json(['message' => 'Sneaker deleted successfully'], 200);

        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to delete the sneakers with that ID' .$e->getMessage()]);
        }
    }

    public function update(Request $request, $id){
        try {
            // retrieve the sneaker whose details to update
            $sneaker = Sneaker::find($id);

            if(!$sneaker){
                return response()->json(['error' => 'The sneaker with that ID does not exist'], 404);
            }

            // validate the incoming request data
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required',
                'brand' => 'required|string',
                'size' => 'required|integer',
                'color' => 'required|string',
                'stock_quantity' => 'required|integer',
                'discount' => 'required'
            ]);


            // extract the values off of the request object
            $name = $request->input('name');
            $description = $request->input('description');
            $price = $request->input('price');
            $brand = $request->input('brand');
            $size = $request->input('size');
            $color = $request->input('color');
            $stock_quantity = $request->input('stock_quantity');
            $discount = $request->input('discount');

            // update the sneaker details
            $sneaker->update([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'brand' => $brand,
                'size' => $size,
                'color' => $color,
                'stock_quantity' => $stock_quantity,
                'discount' => $discount
            ]); 

            // return a successful response
            return response()->json(['message' => 'Sneaker updated succesfully'], 200);


        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to update the sneaker with that ID' .$e->getMessage()]);
        }
    }
}

