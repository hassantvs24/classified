<?php

namespace App\Http\Controllers\Advertisement;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchSaveResource;
use App\Models\SearchSave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SearchSaveController extends Controller
{

    public function index()
    {
        try{
            $table = SearchSave::orderBy('id', 'DESC')->where('users_id', Auth::id())->get();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return SearchSaveResource::collection($table);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'areas_id'              => 'sometimes|nullable|integer|exists:areas,id',
            'product_brands_id'     => 'sometimes|nullable|integer|exists:product_brands,id',
            'product_categories_id' => 'sometimes|nullable|integer|exists:product_categories,id',
            'product_types_id'      => 'sometimes|nullable|integer|exists:product_types,id',
            'companies_id'          => 'sometimes|nullable|integer|exists:companies,id',
            'products_id'           => 'sometimes|nullable|integer|exists:products,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        $data = [
            'areas_id' => $request->areas_id ?? '',
            'product_brands_id' => $request->product_brands_id ?? '',
            'product_categories_id' => $request->product_categories_id ?? '',
            'product_types_id' => $request->product_types_id ?? '',
            'companies_id' => $request->companies_id ?? '',
            'products_id' => $request->products_id ?? '',
        ];

        try{
            $table = new SearchSave();
            $table->name= $request->name;
            $table->search_params= json_encode($data);
            $table->users_id= Auth::id();
            $table->save();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new SearchSaveResource($table);
    }


    public function show($id)
    {
        try{
            $table = SearchSave::find($id);
            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new SearchSaveResource($table);
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
            SearchSave::destroy($id);
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
