<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use App\Models\AttributeLink;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{

    public function index()
    {
        try{
            $table = Attribute::orderBy('id', 'DESC')->get();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AttributeResource::collection($table);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:attributes,name',
            'attribute_sets_id' => 'required|numeric|exists:attribute_sets,id',
            'product_categories_id' => 'required|array',
            'attr_values' => 'required|array',
            'is_filterable' =>  'required|boolean'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        DB::beginTransaction();
            try{

                $table = new Attribute();
                $table->name = $request->name;
                $table->is_filterable = $request->is_filterable;
                $table->attribute_sets_id = $request->attribute_sets_id;
                $table->save();
                $attributes_id = $table->id;

                $categories = $request->product_categories_id;
                foreach ($categories as $cat_id){
                    $attr_link = new AttributeLink();
                    $attr_link->product_categories_id = $cat_id;
                    $attr_link->attributes_id = $attributes_id;
                    $attr_link->save();
                }

                $attr_values = $request->attr_values;
                foreach ($attr_values as $attr_value){
                    $attr_val = new AttributeValue();
                    $attr_val->name = $attr_value;
                    $attr_val->attributes_id = $attributes_id;
                    $attr_val->save();
                }

            }catch (\Exception $ex) {
                DB::rollBack();
                return response()->json(config('naz.db'), config('naz.db_error'));
            }
        DB::commit();

        return new AttributeResource($table);
    }


    public function show($id)
    {
        try{

            $table = Attribute::find($id);

            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AttributeResource($table);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:attributes,name,'.$id,
            'attribute_sets_id' => 'required|numeric|exists:attribute_sets,id',
            'product_categories_id' => 'required|array',
            'attr_values' => 'required|array',
            'is_filterable' =>  'required|boolean'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        DB::beginTransaction();
        try{

            $table = Attribute::find($id);
            $table->name = $request->name;
            $table->is_filterable = $request->is_filterable;
            $table->attribute_sets_id = $request->attribute_sets_id;
            $table->save();

            $categories = $request->product_categories_id;

            AttributeLink::where('attributes_id', $id)->delete();
            foreach ($categories as $cat_id){
                $attr_link = new AttributeLink();
                $attr_link->product_categories_id = $cat_id;
                $attr_link->attributes_id = $id;
                $attr_link->save();
            }

            $attr_values = $request->attr_values;

            AttributeValue::where('attributes_id', $id)->delete();
            foreach ($attr_values as $attr_value){
                $attr_val = new AttributeValue();
                $attr_val->name = $attr_value;
                $attr_val->attributes_id = $id;
                $attr_val->save();
            }

        }catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        DB::commit();

        return new AttributeResource($table);
    }


    public function destroy($id)
    {
        try{

            Attribute::destroy($id);

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
