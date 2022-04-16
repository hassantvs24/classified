<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        try {
            $table = Role::orderBy('id', 'DESC')->get();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return RoleResource::collection($table);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:191|unique:roles,name'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $table = new Role();
            $table->name = $request->name;
            $table->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new RoleResource($table);
    }


    public function show($id)
    {
        try{
            $table = Role::find($id);
            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        return new RoleResource($table);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:191|unique:roles,name,'.$id
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        if($id == 1){
            return response()->json(config('naz.n_found'), config('naz.not_found'));
        }

        try {

            $table = Role::find($id);
            $table->name = $request->name;
            $table->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new RoleResource($table);
    }

    public function destroy($id)
    {
        try{
            if($id == 1){
                return response()->json(config('naz.n_found'), config('naz.not_found'));
            }

            Role::destroy($id);
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }

    public function all_permissions(){
        try {
            $table = Permission::orderBy('name')->get();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return PermissionResource::collection($table);
    }

    public function assign_role(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{
            $table = Role::find($id);

            if($table){
                $table->syncPermissions($request->permissions);
            }else{
                return response()->json(config('naz.n_found'), config('naz.not_found'));
            }

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        return new RoleResource($table);
    }
}
