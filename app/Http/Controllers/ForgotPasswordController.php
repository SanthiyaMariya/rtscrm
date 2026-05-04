<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('employee.forgot_password');
    }

    public function sendOtp(Request $request)
    {
        // 1. Validate the email exists in your users table
        $request->validate(['email' => 'required|email|exists:users,email']);

        // 2. Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // 3. Save OTP to database (You might need a 'otp' column in your users table or a separate table)
        $user = User::where('email', $request->email)->first();
        $user->update(['password_otp' => $otp]); // Assuming you add this column

        // 4. Send the Email (Conceptual)
        // Mail::to($user->email)->send(new OtpMail($otp));

        // 5. Redirect to an OTP verification page
        return redirect()->route('password.verify.form')->with('status', 'An OTP has been sent to your email.');
    }
}