<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomUser;

class CustomUserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */ 

    public function store(Request $request){
        try {
            // validate the request data
            $request->validate([
                'full_name' => 'required|string|max:50',
                'email' => 'required|email|unique:customusers,email',
                'contact' => 'required|string|max:14|min:10',
                'address' => 'required|string',
                'password' => 'required|string'
            ]);

            // create a new object of the custom user
            $user = new CustomUser();

            // assign attributes from the request data
            $user->full_name = $request->input('full_name');
            $user->email = $request->input('email');
            $user->contact = $request->input('contact');
            $user->address = $request->input('address');
            $user->password = $request->input('password');

            // save the custom user instance
            $user->save();

            // return a success response
            return response()->json(['error' => 'User signed up successfully'], 201);

        } catch(\Exception $e) {
            return response()->json(['error' => 'Failed to sign up user: ' .$e->getMessage()], 500);
        }
    }

    public function index(){
        try {
            // fetch all users from the database
            $users = CustomUser::all();

            // return the users in a json object
            return response()->json($users, 200);

        } catch(\Exception $e) {
            return response()->json(['error' => 'Failed to fetch users: ' .$e->getMessage()], 500);
        }
    }

    public function show($id){
        try {
            //get the user with the id
            $user = CustomUser::find($id);

            // check if user exists
            if(!$user){
                return response()->json(['error' => 'User with that ID does not exist'], 404);
            }

            // return the user if they exist
            return response()->json($user, 200);
            
        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to fetch the user: ' .$e->getMessage()], 500);
        }
    }



}
