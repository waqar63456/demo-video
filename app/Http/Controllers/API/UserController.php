<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationMail;
use App\Mail\ForgotPasswordMail;
use App\Models\ContactMessage;
use App\Models\EmailVerification;
use App\Models\newusers;
use App\Models\SubscribeNewsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function userlogin(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find the user by email
        $user = newusers::where('email', $request->email)->first();

        // Check if the user exists and the password matches
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Check if the user is active
        if ($user->is_active != 1) {
            return response()->json(['message' => 'Your account is not active. Please contact support.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the response
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }


    public function sendmail(Request $request)
    {
        Log::info('message', $request->all());
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:newusers,email',
                'phone' => 'required|string',
                'user_type' => 'required|string',
                'password' => 'required|string|min:8',
            ]);

            $verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $data['verification_code'] = $verification_code;


            $user = newusers::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'user_type' => $data['user_type'],
                'password' => Hash::make($data['password']),
                'is_active' => 0,
            ]);


            EmailVerification::create([
                'email' => $data['email'],
                'otp_code' => $verification_code,
            ]);



            Mail::to($data['email'])->send(new EmailVerificationMail($verification_code));

            return response()->json([
                'success' => true,
                'message' => 'Registered successfully. Please verify your email',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500);
        }
    }
    public function resendOtp(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email|exists:newusers,email',
            ]);

            // Generate new OTP
            $verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            // Update OTP in EmailVerification table
            EmailVerification::updateOrCreate(
                ['email' => $data['email']],
                ['otp_code' => $verification_code]
            );

            // Send new email
            Mail::to($data['email'])->send(new EmailVerificationMail($verification_code));

            return response()->json([
                'success' => true,
                'message' => 'A new OTP has been sent to your email.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP: ' . $th->getMessage(),
            ], 500);
        }
    }


    public function sendotp(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        $user = newusers::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found with this email.',
            ], 404);
        }

        $otp = rand(100000, 999999);

        // Save OTP to the database
        EmailVerification::updateOrCreate(
            ['email' => $user->email],
            ['otp_code' => $otp, 'is_verified' => 0]
        );

        // Send OTP via email
        Mail::to($user->email)->send(new ForgotPasswordMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
        ]);
    }
    public function forgetverifyotp(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|numeric',
        ]);

        $emailVerification = EmailVerification::where('email', $data['email'])->first();

        if (!$emailVerification || $emailVerification->otp_code != $data['otp_code']) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP or email.',
            ], 400);
        }

        $emailVerification->update(['is_verified' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.',
        ]);
    }

    public function chnagepassword(Request $request)
    {
        $data = $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'otp_code' => 'required|numeric',
            'email' => 'required|email',
        ]);

        $emailVerification = EmailVerification::where([
            ['email', '=', $data['email']],
            ['is_verified', '=', 1]
        ])->first();

        if (!$emailVerification || $emailVerification->otp_code != $data['otp_code']) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP or email.',
            ], 400);
        }

        $user = newusers::where('email', $data['email'])->first();
        if ($user) {
            $user->update(['password' => Hash::make($data['password'])]);

            // ✅ Automatically log the user in by creating a token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Optionally delete the EmailVerification entry
            // $emailVerification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully.',
                'token' => $token,
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }
    }

    public function verifymail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|numeric',
        ]);

        // Retrieve the email verification record
        $emailVerification = EmailVerification::where('email', $data['email'])->first();

        // Check if the email is already verified
        if ($emailVerification && $emailVerification->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'This email has already been verified. Password already sent to this email.',
            ], 400);
        }

        // Verify the provided code
        if ($emailVerification && $emailVerification->otp_code == $data['verification_code']) {
            // Update the verification status
            $emailVerification->update(['is_verified' => 1]);

            // Retrieve the user
            $user = newusers::where('email', $data['email'])->first();
            if ($user) {
                // Update the user to activate their account
                $user->update(['email_verified_at' => now(), 'is_active' => 1]);

                // Log in the user
                Auth::login($user);

                // Generate a token for the user
                $tokenResult = $user->createToken('authToken');
                $plainTextToken = $tokenResult->plainTextToken;

                // Return a success response with the token and user information
                return response()->json([
                    'success' => true,
                    'message' => 'Email verified successfully.',
                    'token' => $plainTextToken,
                    'user' => $user,
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code or email.',
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        if (auth('sanctum')->user()->tokens()->delete()) {
            return response([
                'status' => 200,
                'message' => 'User Logged Out Successfully'
            ]);
        } else {
            return response([
                'status' => 500,
                'message' => 'something went wrong'
            ]);
        }
    }

    public function contacts(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'required|string|max:20',
            'description' => 'required|string',
        ]);

        // Store the validated data
        $message = ContactMessage::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Contact message submitted successfully.',
            'data'    => $message,
        ]);
    }
    public function subscribenewslatter(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'email' => 'required|email|unique:subscribe_newsletter,email',
        ]);

        $subscription = SubscribeNewsletter::create($validated);

        return response()->json([
            'message' => 'Subscribed successfully!',
            'data' => $subscription
        ], 201);
    }
}
