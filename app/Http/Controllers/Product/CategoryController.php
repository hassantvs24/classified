<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoriesResource;
use App\Models\ProductCategories;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use UploadTrait;

    public function index()
    {
        try{

            $table = ProductCategories::orderBy('id', 'DESC')->get();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return ProductCategoriesResource::collection($table);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:product_categories,name',
            'parents_id' => 'sometimes|nullable|exists:product_categories,id',
            'icon'     => 'sometimes|nullable|image'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = new ProductCategories();
            $table->name = $request->name;
            if (isset($request->parents_id)) {
                $table->parents_id = $request->parents_id;
            }

            if ($request->has('icon')) {
                if (isset($request->icon)) {
                    // Get image file
                    $image = $request->file('icon');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/categories/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $table->icon = $filePath;
                }
            }

            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductCategoriesResource($table);
    }


    public function show($id)
    {
        try{

            $table = ProductCategories::find($id);

            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductCategoriesResource($table);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:product_categories,name,'.$id,
            'parents_id' => 'sometimes|nullable|exists:product_categories,id',
            'icon'     => 'sometimes|nullable|image'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = ProductCategories::find($id);
            $table->name = $request->name;
            if (isset($request->parents_id) && $request->parents_id != $id) {
                $table->parents_id = $request->parents_id;
            }else{
                $table->parents_id = null;
            }

            if ($request->has('icon')) {
                if (isset($request->icon)) {
                    // Get image file
                    $image = $request->file('icon');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/categories/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $table->icon = $filePath;
                }else{
                    $table->icon = null;
                }

            }else{
                $table->icon = null;
            }

            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductCategoriesResource($table);
    }


    public function destroy($id)
    {
        try{
            ProductCategories::where('parents_id', $id)->update(['parents_id' => null]);

            ProductCategories::destroy($id);

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }

    public function category_tree(){
        try{
            $table = ProductCategories::with('parent', 'children')->select('id','name', 'parents_id', 'icon')->get();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        //return ProductCategoriesResource::collection($table);
        return response()->json($table);
    }
}
