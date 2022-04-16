<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'          => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $status = Password::sendResetLink($request->only('email'));

        } catch (\Exception $ex) {
            dd($ex);
            return response()->json([config('naz.db'), config('naz.db_error')]);
        }

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(["message" => 'Reset password link sent on your email id.']);
        }else{
             dd($status);
            return response()->json(["message" => 'Some thing error! Email not send']);
        }
    }

    public function get_reset(Request $request){
        $validator = Validator::make($request->all(), [
            'token'          => 'required',
            'email'          => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        return response()->json(['data' => $request->all()]);
    }

    public function reset(Request $request){
        $validator = Validator::make($request->all(), [
            'token'          => 'required',
            'email'          => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response(['message'=> 'Password reset successfully']);
        }

        return response(['message'=> __($status)], 500);

    }
}
