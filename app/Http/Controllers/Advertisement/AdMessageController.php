<?php

namespace App\Http\Controllers\Advertisement;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdsMessageResource;
use App\Models\AdsMessage;
use App\Models\Ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\Events\MessageSend;
use App\Mail\StartConversation;

class AdMessageController extends Controller
{

    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ads_id'      => 'sometimes|nullable|integer|exists:ads,id',
            'users_id'      => 'sometimes|nullable|integer|exists:users,id',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $tablex =  AdsMessage::orderBy('id', 'ASC');
                if (isset($request->ads_id)) {
                    $tablex->where('ads_id', $request->ads_id);
                }
                if (isset($request->users_id)) {
                    $tablex->where('users_id', $request->users_id);
                }
            $table = $tablex->get();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdsMessageResource::collection($table);
    }

    public function message($id)
    {
        try{

            $table =  AdsMessage::orderBy('id', 'DESC')->where('ads_id', $id)->get();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdsMessageResource::collection($table);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message'       => 'required|string',
            'is_buyer'   => 'required|boolean',
            'ads_id'      => 'required|numeric|exists:ads,id',
            'users_id'      => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = new AdsMessage();
            $table->message        = $request->message;
            $table->is_buyer       = $request->is_buyer;
            $table->ads_id    = $request->ads_id;
            $table->users_id  = $request->users_id;
            $table->save();

            $messages = AdsMessage::where('ads_id', $request->ads_id)->where('users_id', $request->users_id)->get();


            if( count($messages) <= 1 ) {
                $ad = Ads::find($request->ads_id);
                Mail::to($ad->user->email)->send(new StartConversation());
            }

            broadcast( new MessageSend( $request->users_id, new AdsMessageResource($table) ) )->toOthers();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdsMessageResource($table);
    }

    public function destroy($id)
    {
        try{
            AdsMessage::destroy($id);
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
