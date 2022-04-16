<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductTypeResource;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypesController extends Controller
{

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_categories_id' => 'sometimes|nullable|exists:product_categories,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{
            $tablex = ProductType::orderBy('id', 'DESC');

            if (isset($request->product_categories_id)) {
                $tablex->where('product_categories_id', $request->product_categories_id);
            }

            $table = $tablex->get();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return ProductTypeResource::collection($table);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'product_categories_id' => 'required|numeric|exists:product_categories,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = new ProductType();
            $table->name = $request->name;
            $table->product_categories_id = $request->product_categories_id;
            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductTypeResource($table);
    }


    public function show($id)
    {
        try{

            $table = ProductType::find($id);

            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductTypeResource($table);
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'product_categories_id' => 'required|numeric|exists:product_categories,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = ProductType::find($id);
            $table->name = $request->name;
            $table->product_categories_id = $request->product_categories_id;
            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductTypeResource($table);
    }


    public function destroy($id)
    {
        try{

            ProductType::destroy($id);

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
