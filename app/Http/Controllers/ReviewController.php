<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function createReview(Request $request){
        try {
            //code...
            $request->validate([
                'user_id' => 'required|exists:custom_users,id',
                'sneaker_id' => 'required|exists:sneakers,id',
                'rating' => 'required|integer|min:0',
                'review' => 'text'
            ]);

            // save the review
            $review = Review::create([
                'user_id' => $request->input('user_id'),
                'sneaker_id' => $request->input('sneaker_id'),
                'rating' => $request->input('rating'),
                'review' => $request->input('review')
            ]);

            // send success response back
            return response()->json(['message' => 'Review added successfully'], 500);
        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to create a review: ' .$e->getMessage()], 500);
        }
    }

    // fetch all reviews
    public function fetchReviews(){
        try {
            //code...
            // fetch the reviews
            $reviews = Review::all();
            // return the reviews
            return response()->json($reviews, 200);

        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to fetch reviews: ' .$e->getMessage()], 500);
        }
    }

    // delete a single review
    public function deleteReview($id){
        try {
            //code...
            $review = Review::find($id);
            if(!$review){
                return response()->json(['error' => 'Failed to find review with that ID'], 500);
            }
            // delete the review
            $review->delete();

            return response()->json(['message' => 'Review deleted successfully'], 200);
        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to delete the review: ' .$e->getMessage()], 500);
        }
    }

    // fetch reviews for a particular user
    public function fetchUserReviews($id){
        try {
            //code...
            $user_reviews = Review::where('user_id', $id)->get();

            // return the user reviews
            return response()->json($user_reviews, 200);
        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to fetch user reviews: ' .$e->getMessage()], 500);
        }
    }

    // fetch reviews for a particular sneaker
    public function fetchSneakersReviews($id){
        try {
            //code...
            $sneaker_reviews = Review::where('sneaker_id', $id)->get();

            // return the user reviews
            return response()->json($sneaker_reviews, 200);
        } catch (\Exception $e) {
            //throw $e;
            return response()->json(['error' => 'Failed to fetch sneaker reviews: ' .$e->getMessage()], 500);
        }
    }
}
