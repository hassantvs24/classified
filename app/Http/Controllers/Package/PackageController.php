<?php

namespace App\Http\Controllers\Package;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdPackageResource;
use App\Models\AdsPackage;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    use UploadTrait;

    public function index()
    {
        try{
            $table = AdsPackage::orderBy('id', 'DESC')->get();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdPackageResource::collection($table);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|unique:ads_packages,name|max:191',
            'types'      => 'sometimes|nullable|in:Premium,Free',
            'quantity'   => 'required|numeric',
            'price'      => 'required|numeric',
            'expire_day' => 'required|numeric',
            'banner'     => 'sometimes|nullable|image',
            'status '     => 'sometimes|nullable|boolean'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = new AdsPackage();
            $table->name        = $request->name;
            $table->quantity    = $request->quantity;
            $table->expire_day  = $request->expire_day;
            $table->price       = $request->price;
            if (isset($request->types)) {
                $table->types       = $request->types;
            }
            if (isset($request->status)) {
                $table->status      = $request->status;
            }
            $table->description = $request->description;

            if ($request->has('banner')) {
                if (isset($request->banner)) {
                    // Get image file
                    $image = $request->file('banner');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/package/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $table->banner = $filePath;
                }
            }

            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdPackageResource($table);
    }

    public function show($id)
    {
        try{

            $table = AdsPackage::find($id);

            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdPackageResource($table);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:191|unique:ads_packages,name,'.$id,
            'types'      => 'sometimes|nullable|in:Premium,Free',
            'quantity'   => 'required|numeric',
            'price'      => 'required|numeric',
            'expire_day' => 'required|numeric',
            'banner'     => 'sometimes|nullable|image',
            'status '    => 'sometimes|nullable|boolean'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = AdsPackage::find($id);
            $table->name        = $request->name;
            $table->quantity    = $request->quantity;
            $table->expire_day  = $request->expire_day;
            $table->price       = $request->price;
            if (isset($request->types)) {
                $table->types       = $request->types;
            }
            if (isset($request->status)) {
                $table->status      = $request->status;
            }
            $table->description = $request->description;

            if ($request->has('banner')) {
                if (isset($request->banner)) {
                    // Get image file
                    $image = $request->file('banner');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/package/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $table->banner = $filePath;
                }
            }else{
                $table->banner = null;
            }

            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdPackageResource($table);
    }

    public function destroy($id)
    {
        try{
            AdsPackage::destroy($id);
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
