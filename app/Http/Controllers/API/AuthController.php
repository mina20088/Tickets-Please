<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Models\User;
use App\traits\ApiResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('Invalid credentials' , Response::HTTP_UNAUTHORIZED);
        }

        $user = User::firstWhere('email', $request->email);

        $token = $user->createToken('auth_token-' . $user->email ,expiresAt: Carbon::now()->addHour())->plainTextToken;

        return $this->ok('Authenticated', ['token' =>  $token]);
    }


    public function register() {
        return $this->ok('register');
    }


    public function logout(Request $request)
    {
          $request->user()->currentAccessToken()->delete();

          return \response()->json([], Response::HTTP_NO_CONTENT);
    }
}
