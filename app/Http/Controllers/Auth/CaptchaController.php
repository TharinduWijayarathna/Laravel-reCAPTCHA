<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CaptchaController extends Controller
{
    public function showCaptcha()
    {
        return view('auth.captcha');
    }

    public function verifyCaptcha(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => 'required',
        ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = config('services.recaptcha.secret');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
        ]);

        $responseBody = $response->json();

        if ($responseBody['success']) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['captcha' => 'Invalid captcha response.']);
    }
}
