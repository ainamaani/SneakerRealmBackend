<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Sneaker;

class SneakerController extends Controller
{
    public function store(Request $request){
        try {
            // validate the data
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

            // create a new object of the sneaker
            $sneaker = new Sneaker();

            // assign attributes from the request data
            $sneaker->name = $name;
            $sneaker->description = $description;
            $sneaker->price = $price;
            $sneaker->brand = $brand;
            $sneaker->size = $size;
            $sneaker->color = $color;
            $sneaker->stock_quantity = $stock_quantity;
            $sneaker->discount = $discount;

            // save the object
            $sneaker->save();

            // return success response
            return response()->json(['message' => 'Sneaker information has been saved successfully'], 201);

        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to add sneaker information' .$e->getMessage()], 500);
        }
    }

    public function index(){
        try {
            // retrieve all sneakers
            $sneakers = Sneaker::all();

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
            $sneaker = Sneaker::find($id);
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
        
    }
}

