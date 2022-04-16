<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AreaResource;

class AreaController extends Controller
{

    public function index()
    {
        try {
            $areas = Area::orderBy('id', 'DESC')->get();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AreaResource::collection($areas);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|min:3|unique:Areas,name',
            'address'       => 'sometimes|string',
            'longitude'     => 'sometimes|numeric',
            'latitude'      => 'sometimes|numeric',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $area            = new Area();
            $area->name      = $request->name;
            $area->address   = $request->address;
            $area->longitude = $request->longitude;
            $area->latitude  = $request->latitude;

            $area->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AreaResource($area);
    }

    public function show($id)
    {
        try{
            $area = Area::find($id);

            if(!$area)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AreaResource($area);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|min:3|unique:Areas,name,'. $id,
            'address'       => 'sometimes|string',
            'longitude'     => 'sometimes|numeric',
            'latitude'      => 'sometimes|numeric',
        ]);

        if ( $validator->fails() ) return response()->json( $validator->errors(), config('naz.validation') );

        try {
            $area = Area::find($id);
            $area->name = $request->name;
            $area->address   = $request->address;
            $area->longitude = $request->longitude;
            $area->latitude = $request->latitude;

            $area->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AreaResource($area);
    }

    public function destroy($id)
    {
        try{
            Area::destroy($id);
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
