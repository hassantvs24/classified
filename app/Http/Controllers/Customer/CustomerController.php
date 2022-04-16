<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    use UploadTrait;

    public function index()
    {
        try{

            $table = User::orderBy('id', 'DESC')->where('types', 'Customer')->get();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return CustomerResource::collection($table);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'sometimes|nullable|image',
            'status' => 'sometimes|nullable|boolean'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = new User();
            $table->name = $request->name;
            $table->email = $request->email;
            $table->contact = $request->contact;
            $table->website = $request->website;
            $table->address = $request->address;
            $table->description = $request->description;
            $table->password = bcrypt($request->password);
            $table->types = 'Customer';
            if (isset($request->status)){
                $table->status = $request->status;
            }

            if ($request->has('photo')) {
                if (isset($request->photo)) {
                    // Get image file
                    $image = $request->file('photo');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/user/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $table->photo = $filePath;
                }
            }

            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CustomerResource($table);
    }


    public function show($id)
    {
        try{

            $table = User::find($id);

            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CustomerResource($table);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|string|email|unique:users,email,'.$id,
            'password' => 'sometimes|nullable|min:8|confirmed',
            'photo' => 'sometimes|nullable|image',
            'status' => 'sometimes|nullable|boolean'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = User::find($id);
            $table->name = $request->name;
            $table->email = $request->email;
            $table->contact = $request->contact;
            $table->website = $request->website;
            $table->address = $request->address;
            $table->description = $request->description;
            if (isset($request->status)){
                $table->status = $request->status;
            }

            if (isset($request->password)) {
                $table->password = bcrypt($request->password);
            }

            if ($request->has('photo')) {
                if (isset($request->photo)) {
                    // Get image file
                    $image = $request->file('photo');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/user/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $table->photo = $filePath;
                }
            }else{
                $table->photo = null;
            }

            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CustomerResource($table);
    }


    public function destroy($id)
    {
        try{

            User::destroy($id);

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }

    public function change_status(Request $request){
        $validator = Validator::make($request->all(), [
            'users_id' => 'required|array',
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            User::whereIn('id', $request->users_id)->update(['status' => $request->status]);

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.edit'));
    }

    public function customer_register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'sometimes|nullable|image',
            'status' => 'sometimes|nullable|boolean'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        DB::beginTransaction();
        try{

            $table = new User();
            $table->name = $request->name;
            $table->email = $request->email;
            $table->contact = $request->contact;
            $table->website = $request->website;
            $table->address = $request->address;
            $table->description = $request->description;
            $table->password = bcrypt($request->password);
            $table->types = 'Customer';
            if (isset($request->status)){
                $table->status = $request->status;
            }

            if ($request->has('photo')) {
                if (isset($request->photo)) {
                    // Get image file
                    $image = $request->file('photo');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/user/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $table->photo = $filePath;
                }
            }

            $table->save();

            $accessToken = $table->createToken('authToken')->accessToken;

            $data = array(
                'message' => 'Successfully Register.',
                'token_type' => 'Bearer',
                'access_token' => $accessToken
            );

        }catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        DB::commit();
        return response()->json($data);
    }
}
