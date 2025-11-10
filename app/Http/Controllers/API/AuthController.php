<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return $this->apiResponse(null, Response::HTTP_UNAUTHORIZED, 'Invalid credentials');
            }

            $token = $user->createToken('api-token')->plainTextToken;

            $responseData = [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ];

            return $this->apiResponse($responseData, Response::HTTP_OK, 'Login successful.');

        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Validation failed.');
        } catch (Exception $e) {
            return $this->apiResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->user()->currentAccessToken();

            if (! $token) {
                return $this->apiResponse(null, Response::HTTP_BAD_REQUEST, 'No active token found.');
            }

            $token->delete();

            return $this->apiResponse(null, Response::HTTP_OK, 'Logged out successfully.');

        } catch (Exception $e) {
            return $this->apiResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
