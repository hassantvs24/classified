<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $table = new User();
            $table->name = $request->name;
            $table->email = $request->email;
            $table->password = bcrypt($request->password);
            $table->types = 'Admin';
            $table->save();

            $accessToken = $table->createToken('authToken')->accessToken;

            $data = array(
                'message' => 'Successfully Register.',
                'token_type' => 'Bearer',
                'access_token' => $accessToken
            );

        }catch (\Exception $ex) {
            return response()->json($ex,config('naz.db'), config('naz.db_error'));
        }

        return response()->json($data);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {
            /**
             * revoke Old Token
             */
            $userTokens = Auth::user()->tokens;
            foreach($userTokens as $token) {
                $token->revoke();
            }
            /**
             * revoke Old Token
             */

            $data = array(
                'message' => 'Successfully login.',
                'token_type' => 'Bearer',
                'access_token' => Auth::user()->createToken('authToken')->accessToken
            );
            return response()->json($data);
        }

        return response()->json(['message' => 'Unauthenticated! Invalid Credentials'], config('naz.unauthorized'));
    }

    public function me()
    {
        $table = Auth::user();
        return new UserResource($table);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->tokens()->delete();
        }

        return response()->json(['message' => 'Successfully logout.']);
    }

    public function refresh()
    {
        $userTokens = Auth::user()->tokens;
        foreach($userTokens as $token) {
            $token->revoke();
        }

        $data = array(
            'message' => 'Token successfully refresh.',
            'token_type' => 'Bearer',
            'access_token' => Auth::user()->createToken('authToken')->accessToken
        );

        return response()->json($data);
    }
}
