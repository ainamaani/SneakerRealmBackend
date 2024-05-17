<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CustomUser;

class AuthController extends Controller
{
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    * 
    */ 

    public function change_password(Request $request, $id){
        try {
            // validate the request data
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8',
                'new_password_confirmation' => 'required|string|min:8'
            ]);

            // get user trying to change passwords
            $user = CustomUser::find($id);

            // check if user exists
            if(!$user){
                return response()->json(['error' => 'User with that ID does not exist'], 404);
            }

            // check if the new_password 1 is the same as the confirmation password
            if($request->input('new_password') != $request->input('new_password_confirmation')){
                return response()->json(['error' => 'Renter the correct new password'], 400);
            }

            // check if current password is correct
            if(!Hash::check($request->input(('current_password')),$user->password )){
                return response()->json(['error' => 'Enter the correct current password'], 400);
            }

            // change the user's password
            $user->password = Hash::make($request->input(('new_password')));
            $user->save();

            // return a success response after changing the password
            return response()->json(['message' => 'Password changed successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to change passwords: ' .$e->getMessage()], 500);
        }
    }

    public function handle_forgot_password(Request $request){
        try {
            $email = $request->input('email');

            // check if the email exists in the database
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to reset password: ' .$e->getMessage()], 500);
        }
    }
}
