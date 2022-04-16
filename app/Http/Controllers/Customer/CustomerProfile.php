<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdFavouriteResource;
use App\Http\Resources\AdsMessageResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\SubscriptionResource;
use App\Models\AdsFavorites;
use App\Models\AdsMessage;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerProfile extends Controller
{

    public function index(){
        try{

            $table = User::find(Auth::id());

            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CustomerResource($table);
    }

    public function subscriber(){
        try{

            $table = Subscription::where('seller_id', Auth::id())->get();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return SubscriptionResource::collection($table);
    }

    public function my_subscription(){
        try{

            $table = Subscription::where('users_id', Auth::id())->get();


        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return SubscriptionResource::collection($table);
    }

    public function favorite_ads(){
        try{

            $table = AdsFavorites::where('users_id', Auth::id())->get();


        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdFavouriteResource::collection($table);
    }

    public function message(Request $request){
        $validator = Validator::make($request->all(), [
            'ads_id'      => 'required|numeric|exists:ads,id',
            'users_id'      => 'sometimes|nullable|integer|exists:users,id',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $tablex = AdsMessage::where('ads_id', $request->ads_id);
            if (isset($request->users_id)) {
                $tablex->where('users_id', $request->users_id);
            }
            $table = $tablex->get();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdsMessageResource::collection($table);
    }

    public function send_message(Request $request){
        $validator = Validator::make($request->all(), [
            'ads_id'      => 'sometimes|nullable|integer|exists:ads,id'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $tablex = AdsMessage::where('users_id', Auth::id());
            if (isset($request->ads_id)) {
                $tablex->where('ads_id', $request->ads_id);
            }
            $table = $tablex->get();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdsMessageResource::collection($table);
    }
}
