<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\ResetPassword;
use App\Mail\VerifyEmail;
use App\Models\ServiceProvider;
use App\Models\StoryTeller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try{
            $validated = $request->validate([
                'first_name' => 'nullable|string|max:50',
                'last_name' => 'nullable|string|max:50',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'userType' => 'nullable|string|in:service_provider,story_teller',
                'service_category_id' => 'required_if:userType,service_provider|string'
            ]);

            $user = User::create([
                'id' => Str::uuid(),
                'first_name' => $validated['first_name'] ?? null,
                'last_name' => $validated['last_name'] ?? null,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            if($validated['userType'] && $validated['userType'] == 'service_provider'){
                ServiceProvider::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'service_category_id' => $validated['service_category_id']
                ]);
            }else if($validated['userType'] && $validated['userType'] == 'story_teller'){
                StoryTeller::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                ]);
            }

            // generate a signed verification URL
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id]
            );

            try {
                Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));
            } catch (\Throwable $e) {
                Log::error('Failed to send verification email', ['error' => $e->getMessage()]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        } catch (ValidationException $e) {
            Log::error('Registration error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Registration error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try{
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // Conditionally load relationships
            if ($user->service_provider()->exists()) {
                $user->load('service_provider.category');
            }

            if ($user->story_teller()->exists()) {
                $user->load('story_teller');
            }

            if ($user->admin()->exists()) {
                $user->load('admin');
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => new UserResource($user),
                'token' => $token
            ]);
        }catch (ValidationException $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function verifyEmail(Request $request, $id){
        if (! $request->hasValidSignature()) {
            abort(401, 'Invalid or expired verification link.');
        }

        $user = User::findOrFail($id);

        $loginUrl = rtrim(env('FRONTEND_URL', 'http://localhost:3000'), '/') . '/signin';

        if ($user->email_verified_at) {
            return response(
                '<div style="text-align:center;margin-top:40px;font-family:Arial,sans-serif;">
                    <h2 style="color:green;">Email already verified.</h2>
                    <a href="' . $loginUrl . '" style="display:inline-block;margin-top:24px;padding:12px 28px;background:#f9c406;color:#222;text-decoration:none;border-radius:6px;font-weight:bold;">Return to login</a>
                </div>'
            );
        }

        $user->email_verified_at = now();
        $user->save();

        return response(
            '<div style="text-align:center;margin-top:40px;font-family:Arial,sans-serif;">
                <h2 style="color:green;">Email verified successfully.</h2>
                <a href="' . $loginUrl . '" style="display:inline-block;margin-top:24px;padding:12px 28px;background:#f9c406;color:#222;text-decoration:none;border-radius:6px;font-weight:bold;">Return to login</a>
            </div>'
        );
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Generate a signed URL valid for 60 minutes
        $url = URL::temporarySignedRoute(
            'password.reset', now()->addMinutes(60), ['id' => $user->id]
        );

        $resetUrl = rtrim(env('FRONTEND_URL', 'http://localhost:3000'), '/') . '/reset-password/' . $user->id . '?url=' . urlencode($url);

        try {
            Mail::to($user->email)->send(new ResetPassword($user, $resetUrl));
        } catch (\Throwable $e) {
            Log::error('Failed to send password reset email', ['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Password reset link sent to your email.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'password' => 'required|min:6|confirmed',
            'url' => 'required',
        ]);

        $tempRequest = Request::create($request->url, 'GET');

        if (! URL::hasValidSignature($tempRequest)) {
            return response()->json(['message' => 'Invalid or expired reset link.'], 403);
        }

        $user = User::find($request->id);
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successfully.']);
    }
}
