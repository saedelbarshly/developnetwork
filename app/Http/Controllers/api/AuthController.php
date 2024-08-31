<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SendCodeRequest;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Requests\Auth\VerifyCodeRequest;

class AuthController extends Controller
{
    public function signin(Request $request)
    {
        try {
            $user = User::where('phone', $request->phone)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['status' => false, 'message' => "User Not Found !"], 400);
            };

            if (is_null($user->phone_verified_at)) {
                return response()->json(['status' => false, 'message' => "You should verify your phone number"], 400);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'message' => "Login successful",
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => "Somthing went wrong !"], 400);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'phone' => $request->phone,
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'message' => "Register successful , Go to verfiy phone first !",
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => "Somthing went wrong !"], 400);
        }
    }


    
    public function send(SendCodeRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if(is_null($user)){
            return response()->json(['status' => false,'message' => "User Not Found !"]);
        }
        $executed = RateLimiter::attempt(
            'send-message:' . $user->id,
            $perMinute = 1,
            function () use ($user) {
                DB::beginTransaction();
                try {
                    $code = rand(10000, 99999);
                    $user->code = $code;
                    $user->code_expired_at = Carbon::now()->addSeconds(config('auth.code_timeout'));
                    $user->save();
                    DB::commit();
                    Log::channel('verification_code')->info('Verification code sent:', [
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'code' => $code
                    ]);
                    return response()->json(['status' => true, 'message' => "Code Sent Successfully ✅"]);
                } catch (\Throwable $th) {
                    DB::rollback();
                    return response()->json(['status' => false, 'message' => "Somthing went wrong !"]);
                }
            }
        );
        if (!$executed) {
            return response()->json(['status' => false, 'message' => "Too many messages sent!"]);
        }
        return $executed;
    }


    public function verify(VerifyCodeRequest $request)
    {
        try {
            $user = User::where('phone', $request->phone)->first();
            if(is_null($user)){
                return response()->json(['status' => false,'message' => "User Not Found !"]);
            }
            $now = date('Y-m-d H:i:s');
            if ($user->code != $request->code) {
                return response()->json(['status' => false, 'message' => "Invalid Code !"]);
            }
            if ($now > $user->code_expired_at) {
                return response()->json(['status' => false, 'message' => "Expired Code !"]);
            }
            $user->update(['phone_verified_at' => Carbon::now()]);
            return response()->json(['status' => true, 'message' => "Confirmed Successfully ✅ , login agine pls"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => "Somthing went wrong !"]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['status' => true,'message' => "Logged Out Successfully!"]);
    }
}
