<?php

namespace App\Http\Controllers\Advertisement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdsFavorites;
use App\Http\Resources\AdFavouriteResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdFavouriteController extends Controller
{

    public function index( Request $request )
    {
        $validator = Validator::make($request->all(), [
            'users_id'      => 'sometimes|nullable|exists:users,id',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{
            if(Auth::user()->types == 'Admin'){
                $adFavoritex = AdsFavorites::orderBy('id', 'DESC');
                if (isset($request->users_id)) {
                    $adFavoritex->where('users_id', $request->users_id);
                }
                $adFavorite = $adFavoritex->get();
            }else{
                $adFavorite = AdsFavorites::where( 'users_id', Auth::id() )->orderBy('id', 'DESC')->get();
            }
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdFavouriteResource::collection($adFavorite);
    }


    public function store(Request $request )
    {
        $validator = Validator::make($request->all(), [
            'ads_id' => 'required|exists:ads,id',
            'users_id'      => 'required|exists:users,id'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {
            $adFavourite           = new AdsFavorites();
            $adFavourite->ads_id   =  $request->ads_id;
            $adFavourite->users_id =  $request->users_id;
            $adFavourite->save();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdFavouriteResource($adFavourite);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id )
    {
        //
    }

    public function destroy($id)
    {
        try{
            AdsFavorites::destroy($id);
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
