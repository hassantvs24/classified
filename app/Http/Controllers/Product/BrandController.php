<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductBrandResource;
use App\Models\ProductBrand;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    use UploadTrait;

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_categories_id' => 'sometimes|nullable|exists:product_categories,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{
            $tablex = ProductBrand::orderBy('id', 'DESC');

            if (isset($request->product_categories_id)) {
                $tablex->where('product_categories_id', $request->product_categories_id);
            }

            $table = $tablex->get();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return ProductBrandResource::collection($table);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'logo'       => 'sometimes|nullable|image',
            'product_categories_id' => 'required|numeric|exists:product_categories,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = new ProductBrand();
            $table->name = $request->name;
            $table->origin = $request->origin;
            $table->description = $request->description;
            $table->product_categories_id = $request->product_categories_id;

            if ($request->has('logo')) {
                if (isset($request->logo)) {
                    // Get image file
                    $image = $request->file('logo');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/brands/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $table->logo = $filePath;
                }
            }

            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductBrandResource($table);
    }


    public function show($id)
    {
        try{

            $table = ProductBrand::find($id);

        if(!$table)
            return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductBrandResource($table);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'logo'       => 'sometimes|nullable|image',
            'product_categories_id' => 'required|numeric|exists:product_categories,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = ProductBrand::find($id);
            $table->name = $request->name;
            $table->origin = $request->origin;
            $table->description = $request->description;
            $table->product_categories_id = $request->product_categories_id;

            if ($request->has('logo')) {
                if (isset($request->logo)) {
                    // Get image file
                    $image = $request->file('logo');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/brands/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $table->logo = $filePath;
                }
            }else{
                $table->logo = null;
            }

            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductBrandResource($table);
    }


    public function destroy($id)
    {
        try{

            ProductBrand::destroy($id);

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
