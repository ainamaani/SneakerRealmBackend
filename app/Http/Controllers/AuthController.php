<?php

namespace App\Http\Controllers;

use App\Mail\ResetCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\CustomUser;
use App\Models\ResetCodes;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function send_reset_password_code(Request $request){
        DB::beginTransaction();

        try {
            // validate the email in the request
            $request->validate([
                'email' => 'required|email'
            ]);

            // retrieve the email from the request
            $email = $request->input('email');

            // check if the email exists in the database
            $user = CustomUser::where('email',$email)->first();

            if(!$user){
                return response()->json(['error'=>'User with that emil does not exist'], 404);
            }

            // generate the reset code
            $reset_code = mt_rand(100000, 999999);

            $user_id = $user->id;

            // check if the code has already been sent
            $exits = ResetCodes::where('user_id', $user_id)->first();

            // replace the code
            if($exits){
                $exits->reset_code = $reset_code;
                $exits->save();
                // return success response
                return response()->json(['message'=>'Another reset code sent successfully'], 200);

                // send the email
                Mail::to($user->email)->send(new ResetCode($user, $reset_code));
            }

            ResetCodes::create([
                'user_id' => $user_id,
                'reset_code' => $reset_code,
            ]);

            // send the email
            Mail::to($user->email)->send(new ResetCode($user, $reset_code));

            DB::commit();

            return response()->json(['message' => 'Reset code sent successfully'], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to reset password: ' .$e->getMessage()], 500);
        }
    }

    public function fetch_reset_tokens(){
        try {
            // fetch all reset codes
            $reset_tokens = ResetCodes::all();
            // return the reset_tokens
            return response()->json($reset_tokens, 200);
        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to fetch all reset tokens: ' .$e->getMessage()], 500);
        }
    }

    public function reset_forgotten_password(Request $request){
        try {
            // validate the request data
            $request->validate([
                'email' => 'required|email',
                'code' => 'required|integer',
                'new_password' => 'required|string|min:8',
                'new_password_confirmation' => 'required|string|min:8'
            ]);

            // get the values from the variables
            $email = $request->input('email');
            $code = $request->input('code');
            $new_password = $request->input('new_password');
            $new_password_confirmation = $request->input(('new_password_confirmation'));

            // get the user corresponding to that email
            $user = CustomUser::where('email', $email)->first();

            // check if user exists
            if(!$user){
                return response()->json(['error' => 'Enter the correct email'], 404);
            }

            // get the code corresponsing to the user
            $user_resetting = ResetCodes::where('user_id', $user->id)->first();

            // check if it exists
            if(!$user_resetting){
                return response()->json(['error'=>'No reset code was sent to this email'], 400);
            }

            // check if the codes match
            if($user_resetting->reset_code != $code){
                return response()->json(['error'=>'Enter the correct code sent to your email'], 400);
            }

            // check if the two passwords provided match
            if($new_password != $new_password_confirmation){
                return response()->json(['error'=>'The two passwords entered do not match'], 400);
            }

            $user->password = Hash::make($new_password);
            $user->save();

            return response()->json(['error' => 'Password resetted successfully']);

        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to reset forgotten password: ' .$e->getMessage()], 500);
        }
    }

    public function handle_login(Request $request){
        try {
            // validate the login credentials
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8'
            ]);

            // get values off the object
            $email = $request->input('email');
            $password = $request->input('password');

            // check if the user with that email exists
            $user = CustomUser::where('email', $email)->first();
            if(!$user){
                return response()->json(['error' => 'Invalid credentials'], 400);
            }

            // check if password are the same
            $matches = Hash::check($password, $user->password );
            if(!$matches){
                return response()->json(['error' => 'Invalid credentials'], 400);
            }

            // generate a token and log in user
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'User logged in',
                'token' => $token
            ], 200);

        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error'=>'Failed to handle login: ' .$e->getMessage()], 500);
        }
    }
}
