<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\CustomUser;
use App\Mail\UserRegistered;

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

        DB::beginTransaction();

        try {
            // validate the request data
            $request->validate([
                'full_name' => 'required|string|max:50',
                'email' => 'required|email|unique:custom_users,email',
                'contact' => 'required|string|max:14|min:10',
                'address' => 'required|string',
                'password' => 'required|string|min:8',
                'password_confirmation' => 'required|string|min:8'
            ]);

            // check if the two passwords are the same
            if($request->input('password') != $request->input('password_confirmation')){
                return response()->json(['error' => 'First password does not match confirmation password '], 400);
            }

            // create a new object of the custom user
            $user = new CustomUser();

            // assign attributes from the request data
            $user->full_name = $request->input('full_name');
            $user->email = $request->input('email');
            $user->contact = $request->input('contact');
            $user->address = $request->input('address');
            $user->password = Hash::make($request->input(('password')));

            // save the custom user instance
            $user->save();

            // send email after signing up the user
            Mail::to($user->email)->send(new UserRegistered($user));

            DB::commit();

            // return a success response
            return response()->json(['error' => 'User signed up successfully'], 201);

        } catch(\Exception $e) {
            DB::rollBack();
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

    public function destroy($id){
        try {
            // retrieve user corresponding to the id
            $user = CustomUser::find($id);

            // check if the user exists
            if(!$user){
                return response()->json(['error'=>'User with such ID does not exist'], 404);
            }

            // delete the user
            $user->delete();
            // return success response
            return response()->json(['message'=>'User deleted successfully'], 200);

        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error'=>'Failed to delete user: ' .$e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id){
        try {
            //retrieve user details to update
            $user = CustomUser::find($id);

            // check if user exists
            if(!$user){
                return response()->json(['error'=>'The user with that ID does not exist'], 404);
            }

            // validate the request data
            $request->validate([
                'full_name' => 'required|string|max:50',
                'email' => 'required|email|unique:custom_users,email,' .$id,
                'contact' => 'required|string|max:14|min:10',
                'address' => 'required|string',
            ]);

            // update the user details
            $user->update([
                'full_name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'contact' => $request->input('contact'),
                'address' => $request->input('address'),

            ]);

            // return success response
            return response()->json(['message' => 'User updated successfully'], 200);

        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error'=>'Failed to update the user details: ' .$e->getMessage()], 500);
        }
    }



}
