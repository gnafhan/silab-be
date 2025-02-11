<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function getVerificationLink(Request $request){
        // User sudah terverif
        if($request->user()->hasVerifiedEmail()){
            return [
                'message' => 'Email sudah terverifikasi'
            ];
        }

        // Get verif link
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $request->user()->getKey(),
                'hash' => sha1($request->user()->getEmailForVerification()),
            ]
        );

        $urlParts = parse_url($verificationUrl);
        $pathWithQuery = $urlParts['path'] . '?' . $urlParts['query'];
        return response()->json([
            'message'=>'Berhasil melakukan hash auth', 
            'verification_link' => $verificationUrl, 
            'verification_path'=>$pathWithQuery,
        ]);
    }

    public function sendVerificationEmail(Request $request){
        $userEmail = $request->user()->email;

        // Get link dari request
        $verificationUrl = $request->link;

        // Kirim email verifikasi
        Mail::to($userEmail)->queue(new EmailVerification($verificationUrl));

        return response()->json(['message' => 'Email verifikasi telah dikirim.']);
    }

     public function verify(EmailVerificationRequest $request){
        // User sudah terverif
        if($request->user()->hasVerifiedEmail()){
            return [
                'message' => 'Email sudah terverifikasi'
            ];
        }

        // Verifikasi email user
        if($request->user()->markEmailAsVerified()){
            event(new Verified($request->user()));
        }

        // Hapus current token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message'=>'Email terverifikasi']);
    }
}
