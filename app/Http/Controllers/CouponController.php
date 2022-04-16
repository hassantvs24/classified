<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{

    public function index()
    {
        try {
            $coupons = Coupon::orderBy('id', 'DESC')->get();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return CouponResource::collection($coupons);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'             => 'required|string|min:3|unique:coupons,code',
            'amount'           => 'required|numeric',
            'is_percent'       => 'required|boolean',
            'status'           => 'required|in:Active,Used,Inactive',
            'ads_packages_id'  => 'sometimes|nullable|exists:ads_packages,id',
            'users_id'         => 'sometimes|nullable|exists:users,id',
            'expire'           => 'sometimes|nullable|date',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $coupon = new Coupon();
            $coupon->ads_packages_id = $request->ads_packages_id;
            $coupon->users_id        = $request->users_id;
            $coupon->expire =  Carbon::parse($request->expire)->format('Y-m-d H:i:s');
            $coupon->code            = $request->code;
            $coupon->amount          = $request->amount;
            $coupon->is_percent      = $request->is_percent;
            $coupon->status          = $request->status;
            $coupon->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CouponResource($coupon);
    }


    public function show($id)
    {
        try{
            $table = Coupon::find($id);
            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CouponResource($table);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code'             => 'required|string|min:3|unique:coupons,code,'. $id,
            'amount'           => 'required|numeric',
            'is_percent'       => 'required|boolean',
            'status'           => 'required|in:Active,Used,Inactive',
            'ads_packages_id'  => 'sometimes|nullable|exists:ads_packages,id',
            'users_id'         => 'sometimes|nullable|exists:users,id',
            'expire'           => 'sometimes|nullable|date',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $coupon = Coupon::find($id);

            if( isset( $request->ads_packages_id ) ) {
                $coupon->ads_packages_id = $request->ads_packages_id;
            }

            if( isset( $request->ads_packages_id ) ) {
                $coupon->users_id        = $request->users_id;
            }

            if( isset( $request->expire ) ) {
                $coupon->expire =  Carbon::parse($request->expire)->format('Y-m-d H:i:s');
            }

            $coupon->code            = $request->code;
            $coupon->amount          = $request->amount;
            $coupon->is_percent      = $request->is_percent;
            $coupon->status          = $request->status;

            $coupon->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CouponResource($coupon);
    }


    public function destroy($id)
    {
        try{
            Coupon::destroy($id);
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
