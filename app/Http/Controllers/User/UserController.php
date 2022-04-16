<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use UploadTrait;

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'sometimes|nullable|file',
            'role_id' => 'sometimes|nullable|exists:roles,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = new User();
            $table->name = $request->name;
            $table->email = $request->email;
            $table->password = bcrypt($request->password);
            $table->types = 'Admin';

            if ($request->has('photo')) {
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

            $table->save();

            $table->assignRole($request->role_id);

            $accessToken = $table->createToken('authToken')->accessToken;

            $data = array(
                'message' => 'Successfully Register.',
                'token_type' => 'Bearer',
                'access_token' => $accessToken
            );

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|string|email|unique:users,email,'.$id,
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'sometimes|nullable|file',
            'role_id' => 'sometimes|nullable|exists:roles,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = User::find($id);
            $table->name = $request->name;
            $table->email = $request->email;
            $table->password = bcrypt($request->password);
            $table->types = 'Admin';

            if ($request->has('photo')) {
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

            $table->save();

            $table->assignRole($request->role_id);

            $accessToken = $table->createToken('authToken')->accessToken;

            $data = array(
                'message' => 'Successfully Updated.',
                'token_type' => 'Bearer',
                'access_token' => $accessToken
            );

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json($data);
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
}
