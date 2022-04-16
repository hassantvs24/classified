<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductTag;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use UploadTrait;

    public function index()
    {
        try{
            $table = Product::orderBy('id', 'DESC')->get();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return ProductResource::collection($table);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'state' => 'sometimes|nullable|in:Weapon,Accessories,Other',
            'price' => 'required|numeric',
            'is_disable' => 'required|boolean',
            'product_categories_id' => 'required|numeric|exists:product_categories,id',
            'product_types_id' => 'required|numeric|exists:product_types,id',
            'product_brands_id' => 'required|numeric|exists:product_brands,id',
            'attributes_id' => 'sometimes|nullable|array',
            'attributes_val' => 'sometimes|nullable|array',
            'tags_id' => 'sometimes|nullable|array'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        DB::beginTransaction();
        try{

            $table = new Product();
            $table->name = $request->name;
            if (isset($request->state)) {
                $table->state = $request->state;
            }
            $table->descriptions = $request->descriptions;
            $table->is_disable = $request->is_disable;
            $table->price = $request->price;
            $table->product_categories_id = $request->product_categories_id;
            $table->product_types_id = $request->product_types_id;
            $table->product_brands_id = $request->product_brands_id;

            if ($request->has('photo')) {
                // Get image file
                $image = $request->file('photo');
                // Make a image name based on user name and current timestamp
                $name = Str::slug($request->input('name')) . '_' . time();
                // Define folder path
                $folder = '/uploads/products/';
                // Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                // Upload image
                $this->uploadOne($image, $folder, 'public', $name);
                // Set user profile image path in database to filePath
                $table->photo = $filePath;
            }
            $table->save();

            $product_id = $table->id;

            if (isset($request->attributes_id)) {
                $attributes = $request->attributes_id;
                $attributes_val = $request->attributes_val;
                foreach ($attributes as $attributes_id){
                    $prod_attr = new ProductAttribute();
                    $prod_attr->attributes_id = $attributes_id;
                    $prod_attr->products_id = $product_id;
                    $prod_attr->values = json_encode($attributes_val[$attributes_id]);
                    $prod_attr->save();
                }
            }

            if (isset($request->tags_id)) {
                $product_tags = $request->tags_id;
                foreach ($product_tags as $tags_id){
                    $prod_tag = new ProductTag();
                    $prod_tag->tags_id = $tags_id;
                    $prod_tag->products_id = $product_id;
                    $prod_tag->save();
                }
            }


        }catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        DB::commit();

        return new ProductResource($table);
    }


    public function show($id)
    {
        try{

            $table = Product::find($id);

            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductResource($table);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'state' => 'sometimes|nullable|in:Weapon,Accessories,Other',
            'price' => 'required|numeric',
            'is_disable' => 'required|boolean',
            'product_categories_id' => 'required|numeric|exists:product_categories,id',
            'product_types_id' => 'required|numeric|exists:product_types,id',
            'product_brands_id' => 'required|numeric|exists:product_brands,id',
            'attributes_id' => 'sometimes|nullable|array',
            'attributes_val' => 'sometimes|nullable|array',
            'tags_id' => 'sometimes|nullable|array',
            'photo'   => 'sometimes|nullable|image'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        DB::beginTransaction();
        try{

            $table = Product::find($id);
            $table->name = $request->name;
            if (isset($request->state)) {
                $table->state = $request->state;
            }
            $table->descriptions = $request->descriptions;
            $table->price = $request->price;
            $table->is_disable = $request->is_disable;
            $table->product_categories_id = $request->product_categories_id;
            $table->product_types_id = $request->product_types_id;
            $table->product_brands_id = $request->product_brands_id;

            if ($request->has('photo')) {
                if (isset($request->photo)) {
                    // Get image file
                    $image = $request->file('photo');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/products/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $table->photo = $filePath;
                }else{
                    $table->photo = null;
                }
            }else{
                $table->photo = null;
            }
            $table->save();

            ProductAttribute::where('products_id', $id)->delete();

            if (isset($request->attributes_id)) {
                $attributes = $request->attributes_id;
                $attributes_val = $request->attributes_val;
                foreach ($attributes as $attributes_id) {
                    $prod_attr = new ProductAttribute();
                    $prod_attr->attributes_id = $attributes_id;
                    $prod_attr->products_id = $id;
                    $prod_attr->values = json_encode($attributes_val[$attributes_id]);
                    $prod_attr->save();
                }
            }

            ProductTag::where('products_id', $id)->delete();

            if (isset($request->tags_id)) {
                $product_tags = $request->tags_id;
                foreach ($product_tags as $tags_id) {
                    $prod_tag = new ProductTag();
                    $prod_tag->tags_id = $tags_id;
                    $prod_tag->products_id = $id;
                    $prod_tag->save();
                }
            }

        }catch (\Exception $ex) {
            DB::rollBack();
            //dd($ex);
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        DB::commit();

        return new ProductResource($table);
    }

    public function destroy($id)
    {
        try{

            Product::destroy($id);

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
