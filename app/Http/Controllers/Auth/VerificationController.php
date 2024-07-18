<?php
// app/Http/Controllers/Auth/VerificationController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerificationRequest;
use App\Mail\SendVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated
    }

    public function show()
    {
        return view('auth.verify');
    }

    public function verify(VerificationRequest $request)
    {
        $userId = Auth::id();
        $verificationCode = VerificationCode::where('user_id', $userId)->where('code', $request->code)->first();

        if ($verificationCode) {

            $user = User::find($userId);

            if ($user) {
                $user->email_verified_at = now();
                $user->save();

                $verificationCode->delete();

                return redirect()->route('dashboard')->with('status', 'Your email has been verified!');
            }

            return back()->withErrors(['code' => 'User not found.']);
        }

        return back()->withErrors(['code' => 'Invalid verification code.']);
    }

    public function resend(Request $request)
    {
        $user = Auth::user();

        // Generate a new verification code
        $verificationCode = mt_rand(100000, 999999);
        VerificationCode::updateOrCreate(
            ['user_id' => $user->id],
            ['code' => $verificationCode]
        );

        // Send the verification code
        Mail::to($user->email)->send(new SendVerificationCode($verificationCode));

        return back()->with('status', 'verification-link-sent');
    }
}
