<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
class VerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Already Verified'
            ];
        }

        $request->user()->sendEmailVerificationNotification();

        return ['message' => 'verification-link-sent'];
    }

    public function verify(Request $request)
    {
        $id = $request->route('id');
        $user = User::find($id);

        if ($id != $user->getKey()) {
            return [
                'message'=>'Invalid data'
            ];
        }

        if (! hash_equals((string) $request->route('hash'),
            sha1($user->getEmailForVerification()))) {
            return [
                'message'=>'Invalid Security Key'
            ];
        }


        if ($user->hasVerifiedEmail()) {
            return [
                'message' => 'Email already verified'
            ];
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return [
            'message'=>'Email has been verified'
        ];
    }
}
