<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeSetResource;
use App\Models\AttributeSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeSetController extends Controller
{

    public function index()
    {
        try{
            $table = AttributeSet::orderBy('id', 'DESC')->get();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AttributeSetResource::collection($table);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = new AttributeSet();
            $table->name = $request->name;
            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AttributeSetResource($table);
    }


    public function show($id)
    {
        try{

            $table = AttributeSet::find($id);

            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AttributeSetResource($table);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = AttributeSet::find($id);
            $table->name = $request->name;
            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AttributeSetResource($table);
    }


    public function destroy($id)
    {
        try{

            AttributeSet::destroy($id);

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
