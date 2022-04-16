<?php

namespace App\Http\Controllers\Package;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackagePurchaseResource;
use App\Models\AdsPackage;
use App\Models\PurchasePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe;

class PackagePurchaseController extends Controller
{

    public function index()
    {
        try{
            if(Auth::user()->types == 'Admin'){
                $table = PurchasePackage::orderBy('id', 'DESC')->get();
            }else{
                $table = PurchasePackage::orderBy('id', 'DESC')->where('users_id', Auth::id())->get();
            }

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return PackagePurchaseResource::collection($table);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'coupons_id'      => 'sometimes|nullable|numeric',
            'ads_packages_id'      => 'required|numeric|exists:ads_packages,id',
            'users_id'      => 'required|numeric|exists:users,id',
            'companies_id'      => 'sometimes|nullable|exists:companies,id',
            'stripetoken'       => 'required|string'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{
            $today = date('Y-m-d H:i:s');

            $package = AdsPackage::find($request->ads_packages_id);

            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            /*
            $token = Stripe\Token::create([
              'card' => [
                'number' => '4242424242424242',
                'exp_month' => 8,
                'exp_year' => 2022,
                'cvc' => '314',
              ],
            ]);
            */

            $price = (int) floor(  $package->price);

            $payment = Stripe\Charge::create ([
                "amount" => 100 *  $package->price,
                "currency" => "usd",
                "source" => $request->stripetoken,
                "description" => "This payment is tested"
            ]);

            $table = new PurchasePackage();
            $table->name        = $package->name;
            $table->types    = $package->types;
            $table->quantity    = $package->quantity;
            $table->amount    = $package->price;
            $table->expire    = date('Y-m-d H:i:s', strtotime($today. ' + '.$package->expire_day.' days'));
            $table->discount    = $request->discount ?? 0;
            $table->coupons_id     = $request->coupons_id;
            $table->ads_packages_id  = $request->ads_packages_id;
            $table->users_id       = $request->users_id;
            $table->stripe_payment_id = $payment->id;
            if (isset($request->companies_id)) {
                $table->companies_id       = $request->companies_id;
            }
            $table->save();



            

        }catch (\Exception $ex) {
            dd($ex);
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        return new PackagePurchaseResource($table);
    }

    public function show($id)
    {
        try{

            $table = PurchasePackage::find($id);

            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new PackagePurchaseResource($table);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'coupons_id'      => 'sometimes|nullable|numeric',
            'ads_packages_id'      => 'required|numeric|exists:ads_packages,id',
            'users_id'      => 'required|numeric|exists:users,id',
            'companies_id'      => 'sometimes|nullable|exists:companies,id',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = PurchasePackage::find($id);
            $ads_packages_id = $table->ads_packages_id;
            if($ads_packages_id != $request->ads_packages_id){
                $package = AdsPackage::find($request->ads_packages_id);

                $table->expire    = date('Y-m-d H:i:s', strtotime($table->created_at. ' + '.$package->expire_day.' days'));

                $table->name = $package->name;
                $table->types = $package->types;
                $table->quantity  = $package->quantity;
                $table->amount = $package->price;
            }

            $table->ads_packages_id = $request->ads_packages_id;
            $table->discount = $request->discount ?? 0;
            $table->coupons_id = $request->coupons_id;
            $table->users_id = $request->users_id;
            if (isset($request->companies_id)) {
                $table->companies_id       = $request->companies_id;
            }
            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        return new PackagePurchaseResource($table);
    }

    public function destroy($id)
    {
        try{
            PurchasePackage::destroy($id);
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }

    public function my_order()
    {
        try{
            $table = PurchasePackage::orderBy('id', 'DESC')->where('users_id', Auth::id())->get();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return PackagePurchaseResource::collection($table);
    }

    public function payment_history(Request $request){

        try{
            $tablex = PurchasePackage::orderBy('id', 'DESC');

            if (isset($request->date_range)) {
                $dates = db_range($request->date_range);
                $tablex->whereBetween('created_at', $dates);
            }

            if (isset($request->ads_packages_id)) {
                $tablex->where('ads_packages_id', $request->ads_packages_id);
            }

            if (isset($request->users_id)) {
                $tablex->where('users_id', $request->users_id);
            }

            $table = $tablex->paginate(config('naz.paginate'));
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return PackagePurchaseResource::collection($table);
    }
}
