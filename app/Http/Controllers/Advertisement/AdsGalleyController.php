<?php

namespace App\Http\Controllers\Advertisement;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdPhotosResource;
use App\Models\AdsPhoto;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdsGalleyController extends Controller
{
    use UploadTrait;

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ads_id'           => 'sometimes|nullable|integer|exists:ads,id',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {
            $tablex = AdsPhoto::orderBy('id', 'DESC');
            if(isset($request->ads_id)){
                $tablex->where('ads_id', $request->ads_id);
            }
            $table = $tablex->get();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdPhotosResource::collection($table);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ads_id'          => 'required|integer|exists:ads,id',
            'name'                 => 'required|image',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $table            = new AdsPhoto();
            $table->ads_id      = $request->ads_id;
            if ($request->has('name')) {
                $image      = $request->file('name');
                $photo_name      = $request->ads_id . '_gallery_' . time();
                $folder    = '/uploads/ads/';
                $filePath   = $folder . $photo_name . '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $photo_name);
                $table->name = $filePath;
            }

            $table->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdPhotosResource($table);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'ads_id'          => 'required|integer|exists:ads,id',
            'name'                 => 'sometimes|nullable|image',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $table            = AdsPhoto::find($id);
            $table->ads_id    = $request->ads_id;
            if ($request->has('name')) {
                $image      = $request->file('name');
                $photo_name      = $request->ads_id . '_gallery_' . time();
                $folder    = '/uploads/ads/';
                $filePath   = $folder . $photo_name . '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $photo_name);
                $table->name = $filePath;
            }

            $table->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdPhotosResource($table);
    }

    public function destroy($id)
    {
        try{
            AdsPhoto::destroy($id);
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
