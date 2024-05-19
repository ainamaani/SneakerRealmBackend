<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(){
        try {
            // retrieve all accounts
            $accounts = Account::all();

            // return a success response
            return response()->json($accounts, 200);
        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to fetch the accounts' .$e->getMessage()]);
        }
    }

    public function deposit(Request $request){
        try {
            // validate the data
            $request->validate([
                'account_number' => 'required|integer|min:10',
                'amount' => 'required|integer'
            ]);

            $account_number = $request->input('account_number');
            $amount = (float)($request->input('amount'));

            // get the account with the account number
            $account = Account::where('account_number', $account_number)->first();

            // check if account exists
            if(!$account){
                return response()->json(['error' => 'An account with that account number does not exist'], 404);
            }

            // check if the amount being deposited is above 1000
            if($amount < 1000){
                return response()->json(['error' => 'The minimum amount that you can deposit is 1000 UGX'], 400);
            }

            // update the account balance
            $new_balance = (float)$account->account_balance + $amount;
            $account->account_balance = $new_balance;
            $account->save();

            // return success response
            return response()->json(["message" => "Deposit handled successfully, new balance is {$account->account_balance}"], 200);

        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to handle deposit' .$e->getMessage()], 500);
        }
    }
}
