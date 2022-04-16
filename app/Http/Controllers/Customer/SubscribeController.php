<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubscribeController extends Controller
{

    public function index()
    {
        try{

            $table = Subscription::orderBy('id', 'DESC')->where('users_id', Auth::id())->get();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return SubscriptionResource::collection($table);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seller_id' => 'required|max:191|not_in:'.Auth::id().'|exists:users,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $table = new Subscription();
            $table->seller_id = $request->seller_id;
            $table->users_id = Auth::id();
            $table->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new SubscriptionResource($table);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        try{
            Subscription::destroy($id);
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
