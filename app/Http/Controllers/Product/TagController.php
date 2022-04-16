<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TagResource;

class TagController extends Controller
{

    public function index()
    {
        try {
            $tags = Tag::all();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return TagResource::collection($tags);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:tags,name',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $tag       = new Tag();
            $tag->name = $request->name;

            $tag->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new TagResource($tag);
    }


    public function show($id)
    {
        try{
            $table = Tag::find($id);
            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new TagResource($table);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:tags,name,'.$id,
        ]);

        if ( $validator->fails() ) return response()->json( $validator->errors(), config('naz.validation') );

        try{

            $tag = Tag::find($id);
            $tag->name = $request->name;

            $tag->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new TagResource($tag);
    }


    public function destroy($id)
    {
        try{
            Tag::destroy($id);
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
